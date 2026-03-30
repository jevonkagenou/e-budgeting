<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Budget;
use App\Models\Reimbursement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReimbursementController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $reimbursements = Reimbursement::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar riwayat pengajuan berhasil diambil',
            'data' => $reimbursements
        ], 200);
    }

    public function pendingList(Request $request)
    {
        $user = $request->user();

        if (!$user->hasAnyRole(['manager', 'admin'])) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Anda tidak memiliki izin untuk melihat daftar ini.'
            ], 403);
        }

        $query = Reimbursement::with('user:id,name')->where('status', 'pending');

        if ($user->hasRole('manager')) {
            $query->where('division_id', $user->division_id);
        }

        $pendingReimbursements = $query->orderBy('created_at', 'asc')->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar pengajuan menunggu persetujuan berhasil dimuat',
            'data' => $pendingReimbursements
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'budget_id' => 'required|exists:budgets,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:1000',
            'receipt' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $recentSubmission = Reimbursement::where('user_id', Auth::id())
            ->where('budget_id', $request->budget_id)
            ->where('amount', $request->amount)
            ->where('created_at', '>=', now()->subMinute())
            ->exists();

        if ($recentSubmission) {
            return response()->json([
                'message' => 'Sistem mendeteksi pengajuan ganda. Mohon tunggu 1 menit sebelum mengirim pengajuan yang sama.'
            ], 429);
        }

        $budget = Budget::with('fiscalYear')->findOrFail($request->budget_id);

        if (!$budget->fiscalYear->is_active || now() > $budget->end_date || now() > $budget->fiscalYear->end_date) {
            return response()->json([
                'message' => 'Pengajuan ditolak: Masa berlaku anggaran ini telah habis atau tahun buku sudah ditutup.'
            ], 400);
        }

        $remainingBalance = $budget->total_amount - $budget->used_amount;
        if ($request->amount > $remainingBalance) {
            return response()->json([
                'message' => 'Pengajuan ditolak: Sisa pagu anggaran tidak mencukupi.',
                'remaining_balance' => $remainingBalance
            ], 400);
        }

        $receiptPath = null;
        if ($request->hasFile('receipt')) {
            $receiptPath = $request->file('receipt')->store('receipts', 'public');
        }

        $reimbursement = Reimbursement::create([
            'user_id' => Auth::id(),
            'budget_id' => $request->budget_id,
            'title' => $request->title,
            'description' => $request->description,
            'amount' => $request->amount,
            'receipt_path' => $receiptPath,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Pengajuan dana berhasil dikirim',
            'data' => $reimbursement->load('budget')
        ], 201);
    }

    public function updateStatus(Request $request, $id)
    {
        $user = $request->user();

        if (!$user->hasAnyRole(['manager', 'admin'])) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:approved,rejected',
            'rejection_reason' => 'required_if:status,rejected|string|max:255|nullable'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Data tidak valid', 'data' => $validator->errors()], 422);
        }

        $reimbursement = Reimbursement::find($id);

        if (!$reimbursement) {
            return response()->json(['success' => false, 'message' => 'Data pengajuan tidak ditemukan'], 404);
        }

        if ($reimbursement->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Pengajuan ini sudah diproses.'], 400);
        }

        try {
            DB::beginTransaction();

            if ($request->status === 'approved') {
                $budget = Budget::where('id', $reimbursement->budget_id)
                    ->lockForUpdate()
                    ->first();

                $remainingBalance = $budget->total_amount - $budget->used_amount;

                if ($remainingBalance < $reimbursement->amount) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => 'Gagal menyetujui! Sisa saldo anggaran tidak mencukupi untuk nominal ini.'
                    ], 400);
                }

                $budget->update([
                    'used_amount' => $budget->used_amount + $reimbursement->amount
                ]);
            }

            $reimbursement->update([
                'status' => $request->status,
                'action_by' => $user->id,
                'rejection_reason' => $request->status === 'rejected' ? $request->rejection_reason : null,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Status pengajuan berhasil diperbarui menjadi ' . strtoupper($request->status),
                'data' => $reimbursement
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada sistem saat memproses pengajuan.'
            ], 500);
        }
    }
}
