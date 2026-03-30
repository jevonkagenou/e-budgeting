<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Division;
use App\Models\FiscalYear;
use App\Models\BudgetCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class BudgetController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $query = Budget::with(['division', 'creator', 'fiscalYear', 'budgetCategory']);

        if ($search) {
            $query->where('name', 'ilike', "%{$search}%")
                ->orWhereHas('division', function ($q) use ($search) {
                    $q->where('name', 'ilike', "%{$search}%");
                })
                ->orWhereHas('budgetCategory', function ($q) use ($search) {
                    $q->where('name', 'ilike', "%{$search}%");
                })
                ->orWhereHas('fiscalYear', function ($q) use ($search) {
                    $q->where('year', 'ilike', "%{$search}%");
                });
        }

        $budgets = $query->latest()->paginate(10);

        $divisions = Division::all();
        $fiscalYears = FiscalYear::orderBy('year', 'desc')->get();
        $categories = BudgetCategory::all();

        return view('budgets.index', compact('budgets', 'divisions', 'fiscalYears', 'categories', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fiscal_year_id' => [
                'required',
                'exists:fiscal_years,id',
                Rule::unique('budgets')->where(function ($query) use ($request) {
                    return $query->where('budget_category_id', $request->budget_category_id)
                        ->where('division_id', $request->division_id)
                        ->whereNull('deleted_at');
                })
            ],
            'budget_category_id' => 'required|exists:budget_categories,id',
            'division_id' => 'required|exists:divisions,id',
            'name' => 'required|string|max:255',
            'total_amount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ], [
            'fiscal_year_id.unique' => 'Gagal: Pagu anggaran untuk Divisi dan Kategori ini sudah pernah dibuat pada Tahun Anggaran tersebut! Silakan edit data yang sudah ada.'
        ]);

        $fiscalYear = FiscalYear::findOrFail($request->fiscal_year_id);

        if ($request->start_date < $fiscalYear->start_date || $request->end_date > $fiscalYear->end_date) {
            return back()->withErrors([
                'start_date' => 'Tanggal mulai dan berakhir harus berada dalam rentang tahun fiskal (' . $fiscalYear->start_date . ' s/d ' . $fiscalYear->end_date . ').'
            ])->withInput();
        }

        Budget::create([
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

        return back()->with('success', 'Pagu anggaran berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $budget = Budget::with('fiscalYear')->findOrFail($id);

        if (!$budget->fiscalYear->is_active) {
            return back()->with('error', 'Akses ditolak: Tahun Anggaran sudah ditutup. Data historis tidak boleh diubah.');
        }

        $request->validate([
            'fiscal_year_id' => 'required|exists:fiscal_years,id',
            'budget_category_id' => 'required|exists:budget_categories,id',
            'division_id' => 'required|exists:divisions,id',
            'name' => 'required|string|max:255',
            'total_amount' => 'required|numeric|min:' . $budget->used_amount,
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $fiscalYear = FiscalYear::findOrFail($request->fiscal_year_id);

        if ($request->start_date < $fiscalYear->start_date || $request->end_date > $fiscalYear->end_date) {
            return back()->withErrors([
                'start_date' => 'Tanggal mulai dan berakhir harus berada dalam rentang tahun fiskal (' . $fiscalYear->start_date . ' s/d ' . $fiscalYear->end_date . ').'
            ])->withInput();
        }

        $budget->update([
            'fiscal_year_id' => $request->fiscal_year_id,
            'budget_category_id' => $request->budget_category_id,
            'division_id' => $request->division_id,
            'name' => $request->name,
            'total_amount' => $request->total_amount,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return back()->with('success', 'Data anggaran berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $budget = Budget::with('fiscalYear')->findOrFail($id);

        if (!$budget->fiscalYear->is_active) {
            return back()->with('error', 'Akses ditolak: Tahun Anggaran sudah ditutup. Data historis tidak boleh dihapus.');
        }

        $budget->delete();

        return back()->with('success', 'Anggaran berhasil dihapus dari sistem!');
    }
}
