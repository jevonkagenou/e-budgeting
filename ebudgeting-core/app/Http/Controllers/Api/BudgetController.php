<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use App\Models\Division;
use App\Models\FiscalYear;
use App\Models\BudgetCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BudgetController extends Controller
{
    /**
     * GET /budgets/form-metadata
     * Menyediakan data dropdown untuk form buat/edit anggaran.
     */
    public function formMetadata(Request $request)
    {
        $user = $request->user();
        if (!$user->hasRole('manager')) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'fiscal_years' => FiscalYear::where('is_active', true)->get(['id', 'year', 'start_date', 'end_date']),
                'divisions' => Division::orderBy('name')->get(['id', 'name']),
                'budget_categories' => BudgetCategory::orderBy('name')->get(['id', 'name']),
            ],
        ], 200);
    }

    public function index(Request $request)
    {
        $user = $request->user();

        if (!$user->hasRole('manager')) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak.'
            ], 403);
        }

        $search = $request->input('search');

        $query = Budget::with(['division', 'creator', 'fiscalYear', 'budgetCategory']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('division', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('budgetCategory', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('fiscalYear', function ($q2) use ($search) {
                        $q2->where('year', 'like', "%{$search}%");
                    });
            });
        }

        // Add scope for manager: maybe only see budgets for their managed divisions?
        // Wait, web controller BudgetController doesn't scope by manager division in index. It just shows all.
        // I will follow web controller exactly.

        $budgets = $query->latest()->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'Data pagu anggaran berhasil dimuat',
            'data' => $budgets
        ], 200);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        if (!$user->hasRole('manager')) return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);

        $validator = Validator::make($request->all(), [
            'fiscal_year_id'     => 'required|exists:fiscal_years,id',
            'budget_category_id' => 'required|exists:budget_categories,id',
            'division_id'        => 'required|exists:divisions,id',
            'name'               => 'required|string|max:255',
            'total_amount'       => 'required|numeric|min:1000000',
            'start_date'         => 'required|date',
            'end_date'           => 'required|date|after:today|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Cek duplikasi kombinasi fiscal_year + kategori + divisi secara manual agar pesan lebih informatif
        $duplicate = Budget::where('fiscal_year_id', $request->fiscal_year_id)
            ->where('budget_category_id', $request->budget_category_id)
            ->where('division_id', $request->division_id)
            ->whereNull('deleted_at')
            ->exists();

        if ($duplicate) {
            return response()->json([
                'success' => false,
                'message' => 'Anggaran dengan kombinasi Tahun Anggaran, Kategori, dan Divisi yang sama sudah ada. Silakan pilih kombinasi yang berbeda.',
            ], 422);
        }

        $fiscalYear = FiscalYear::findOrFail($request->fiscal_year_id);

        if ($request->start_date < $fiscalYear->start_date || $request->end_date > $fiscalYear->end_date) {
            return response()->json([
                'success' => false,
                'message' => 'Tanggal mulai dan berakhir harus berada dalam rentang tahun fiskal.'
            ], 400);
        }

        $budget = Budget::create([
            'fiscal_year_id' => $request->fiscal_year_id,
            'budget_category_id' => $request->budget_category_id,
            'division_id' => $request->division_id,
            'name' => $request->name,
            'total_amount' => $request->total_amount,
            'used_amount' => 0,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'created_by' => Auth::id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pagu anggaran berhasil ditambahkan!',
            'data' => $budget
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        if (!$user->hasRole('manager')) return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);

        $budget = Budget::with('fiscalYear')->find($id);

        if (!$budget) {
            return response()->json(['success' => false, 'message' => 'Anggaran tidak ditemukan.'], 404);
        }

        if (!$budget->fiscalYear->is_active) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak: Tahun Anggaran sudah ditutup.'], 403);
        }

        // Validasi dulu — SEBELUM query ke DB lain
        $pendingAmount = \App\Models\Reimbursement::where('budget_id', $budget->id)
            ->where('status', 'pending')
            ->sum('amount');

        $minAllowed = max(1000000, $budget->used_amount + $pendingAmount);

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'fiscal_year_id'     => 'required|exists:fiscal_years,id',
            'budget_category_id' => 'required|exists:budget_categories,id',
            'division_id'        => 'required|exists:divisions,id',
            'name'               => 'required|string|max:255',
            'total_amount'       => 'required|numeric|min:' . $minAllowed,
            'start_date'         => 'required|date',
            'end_date'           => 'required|date|after:today|after_or_equal:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors'  => $validator->errors(),
            ], 422);
        }

        // Cek apakah anggaran sudah kadaluarsa sepenuhnya
        if (now()->format('Y-m-d') > $budget->end_date && now()->format('Y-m-d') > $request->end_date) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak: Pagu ini telah kadaluwarsa.'], 403);
        }

        // Cek duplikasi kombinasi
        $duplicate = Budget::where('fiscal_year_id', $request->fiscal_year_id)
            ->where('budget_category_id', $request->budget_category_id)
            ->where('division_id', $request->division_id)
            ->whereNull('deleted_at')
            ->where('id', '!=', $budget->id)
            ->exists();

        if ($duplicate) {
            return response()->json([
                'success' => false,
                'message' => 'Anggaran dengan kombinasi Tahun Anggaran, Kategori, dan Divisi yang sama sudah ada.',
            ], 422);
        }

        $fiscalYear = FiscalYear::findOrFail($request->fiscal_year_id);

        if ($request->start_date < $fiscalYear->start_date || $request->end_date > $fiscalYear->end_date) {
            return response()->json([
                'success' => false,
                'message' => 'Tanggal mulai dan berakhir harus berada dalam rentang tahun fiskal.'
            ], 400);
        }

        $budget->update([
            'fiscal_year_id'     => $request->fiscal_year_id,
            'budget_category_id' => $request->budget_category_id,
            'division_id'        => $request->division_id,
            'name'               => $request->name,
            'total_amount'       => $request->total_amount,
            'start_date'         => $request->start_date,
            'end_date'           => $request->end_date,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data anggaran berhasil diperbarui!',
            'data'    => $budget
        ], 200);
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        if (!$user->hasRole('manager')) return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);

        $budget = Budget::with('fiscalYear')->find($id);

        if (!$budget) {
            return response()->json(['success' => false, 'message' => 'Anggaran tidak ditemukan.'], 404);
        }

        if ($budget->used_amount > 0 || \App\Models\Reimbursement::where('budget_id', $budget->id)->exists()) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak: Anggaran memiliki riwayat.'], 403);
        }

        if (!$budget->fiscalYear->is_active) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak: Tahun Anggaran sudah ditutup.'], 403);
        }

        $budget->delete();

        return response()->json([
            'success' => true,
            'message' => 'Anggaran berhasil dihapus dari sistem!'
        ], 200);
    }
}
