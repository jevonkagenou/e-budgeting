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

            $budget = Budget::where('id', $reimbursement->budget_id)
                ->lockForUpdate()
                ->firstOrFail();

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
        $reimbursement = Reimbursement::withTrashed()->findOrFail($id);
        $reimbursement->forceDelete();

        return back()->with('success', 'Pengajuan dana beserta struk lampirannya telah dihapus permanen.');
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

        return $pdf->download('LPJ_Reimbursement_' . date('Ymd_His') . '.pdf');
    }
}
