<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Reimbursement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class ReimbursementController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->input('search');
        $status = $request->input('status');

        $query = Reimbursement::with(['user.division', 'budget.fiscalYear', 'actionBy'])->latest();

        /** @var \App\Models\User $user */
        if ($user->hasRole('staff')) {
            $query->where('user_id', $user->id);
        } elseif ($user->hasRole('manager')) {
            $managedDivisionIds = $user->managedDivisions->pluck('id')->toArray();
            $query->whereHas('user', function ($q) use ($managedDivisionIds) {
                $q->whereIn('division_id', $managedDivisionIds);
            });
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'ilike', "%{$search}%")
                    ->orWhere('description', 'ilike', "%{$search}%")
                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('name', 'ilike', "%{$search}%");
                    });
            });
        }

        if ($status && in_array($status, ['pending', 'approved', 'rejected'])) {
            $query->where('status', $status);
        }

        $reimbursements = $query->paginate(10);

        $budgetsQuery = Budget::whereHas('fiscalYear', function ($q) {
            $q->whereDate('end_date', '>=', now())
                ->where('is_active', true);
        })
            ->whereRaw('total_amount > used_amount');

        if (!$user->hasRole('admin')) {
            $budgetsQuery->where('division_id', $user->division_id);
        }

        $budgets = $budgetsQuery->get();

        return view('reimbursements.index', compact('reimbursements', 'budgets', 'search', 'status'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'budget_id' => 'required|exists:budgets,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:1000',
            'receipt' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $recentSubmission = Reimbursement::where('user_id', Auth::id())
            ->where('budget_id', $request->budget_id)
            ->where('amount', $request->amount)
            ->where('created_at', '>=', now()->subMinute())
            ->exists();

        if ($recentSubmission) {
            return back()->with('error', 'Sistem mendeteksi pengajuan ganda. Mohon tunggu 1 menit sebelum mengirim pengajuan yang sama.')->withInput();
        }

        $budget = Budget::with('fiscalYear')->findOrFail($request->budget_id);

        if (!$budget->fiscalYear->is_active || now() > $budget->end_date || now() > $budget->fiscalYear->end_date) {
            return back()->with('error', 'Pengajuan ditolak: Masa berlaku anggaran ini telah habis atau tahun buku sudah ditutup.')->withInput();
        }

        $remainingBalance = $budget->total_amount - $budget->used_amount;
        if ($request->amount > $remainingBalance) {
            return back()->with('error', 'Pengajuan ditolak: Sisa pagu anggaran (Rp ' . number_format($remainingBalance, 0, ',', '.') . ') tidak mencukupi untuk nominal ini.')->withInput();
        }

        $receiptPath = null;
        if ($request->hasFile('receipt')) {
            $receiptPath = $request->file('receipt')->store('receipts', 'public');
        }

        Reimbursement::create([
            'user_id' => Auth::id(),
            'budget_id' => $request->budget_id,
            'title' => $request->title,
            'description' => $request->description,
            'amount' => $request->amount,
            'receipt_path' => $receiptPath,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Pengajuan dana berhasil dikirim dan menunggu persetujuan.');
    }

    public function approve($id)
    {
        return DB::transaction(function () use ($id) {
            $reimbursement = Reimbursement::findOrFail($id);

            if ($reimbursement->status !== 'pending') {
                return back()->with('error', 'Status pengajuan sudah diproses sebelumnya!');
            }

            $budget = Budget::with('fiscalYear')
                ->where('id', $reimbursement->budget_id)
                ->lockForUpdate()
                ->firstOrFail();

            if (!$budget->fiscalYear->is_active || now() > $budget->end_date || now() > $budget->fiscalYear->end_date) {
                return back()->with('error', 'Gagal menyetujui: Masa berlaku anggaran ini telah habis atau tahun buku sudah ditutup.');
            }

            $remainingBalance = $budget->total_amount - $budget->used_amount;
            if ($remainingBalance < $reimbursement->amount) {
                return back()->with('error', 'Gagal menyetujui! Sisa saldo anggaran tidak mencukupi untuk nominal ini.');
            }

            $budget->update([
                'used_amount' => $budget->used_amount + $reimbursement->amount
            ]);

            $reimbursement->update([
                'status' => 'approved',
                'action_by' => Auth::id(),
            ]);

            return back()->with('success', 'Pengajuan disetujui! Saldo anggaran otomatis terpotong.');
        });
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:255'
        ]);

        $reimbursement = Reimbursement::findOrFail($id);

        if ($reimbursement->status !== 'pending') {
            return back()->with('error', 'Status pengajuan sudah diproses sebelumnya!');
        }

        $reimbursement->update([
            'status' => 'rejected',
            'action_by' => Auth::id(),
            'rejection_reason' => $request->rejection_reason,
        ]);

        return back()->with('success', 'Pengajuan dana berhasil ditolak.');
    }

    public function destroy($id)
    {
        $reimbursement = Reimbursement::findOrFail($id);

        if ($reimbursement->status !== 'pending') {
            return back()->with('error', 'Akses ditolak: Data yang sudah diproses (Disetujui/Ditolak) tidak boleh dihapus demi integritas audit.');
        }

        $reimbursement->delete();

        return back()->with('success', 'Pengajuan dana berhasil dibatalkan dan dihapus.');
    }

    public function exportPdf(Request $request)
    {
        $query = Reimbursement::with(['user.division', 'budget.fiscalYear', 'budget.budgetCategory', 'actionBy'])
            ->where('status', 'approved');

        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('updated_at', '>=', $request->start_date);
        }

        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('updated_at', '<=', $request->end_date);
        }

        $reimbursements = collect();

        $query->latest('updated_at')->chunk(500, function ($chunk) use ($reimbursements) {
            foreach ($chunk as $item) {
                $reimbursements->push($item);
            }
        });

        $pdf = Pdf::loadView('reimbursements.laporan_pdf', compact('reimbursements', 'request'))
            ->setPaper('a4', 'landscape');

        return $pdf->stream('LPJ_Reimbursement_' . date('Ymd_His') . '.pdf');
    }
}
