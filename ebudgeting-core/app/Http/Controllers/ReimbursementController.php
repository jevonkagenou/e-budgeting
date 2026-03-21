<?php

namespace App\Http\Controllers;

use App\Models\Budget;
use App\Models\Reimbursement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReimbursementController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->input('search');
        $status = $request->input('status');

        $query = Reimbursement::with(['user.division', 'budget', 'actionBy'])->latest();

        /** @var \App\Models\User $user */
        if ($user->hasRole('staff')) {
            $query->where('user_id', $user->id);
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

        $budgetsQuery = Budget::whereDate('end_date', '>=', now())
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
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:1000',
            'receipt' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
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
        $reimbursement = Reimbursement::findOrFail($id);

        if ($reimbursement->status !== 'pending') {
            return back()->with('error', 'Status pengajuan sudah diproses sebelumnya!');
        }

        $budget = $reimbursement->budget;
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
}
