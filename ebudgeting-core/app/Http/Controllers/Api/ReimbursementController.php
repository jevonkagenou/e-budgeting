<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reimbursement;
use App\Models\FiscalYear;
use Illuminate\Support\Facades\Validator;

class ReimbursementController extends Controller
{

public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        $reimbursements = \App\Models\Reimbursement::where('user_id', $user->id)
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
        /** @var \App\Models\User $user */
        $user = $request->user();

        if (!$user->hasAnyRole(['manager', 'admin'])) {
            return response()->json([
                'success' => false,
                'message' => 'Akses ditolak. Anda tidak memiliki izin untuk melihat daftar ini.'
            ], 403);
        }

        $query = \App\Models\Reimbursement::with('user:id,name')->where('status', 'pending');

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
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:150',
            'date' => 'required|date',
            'amount' => 'required|numeric|min:1000',
            'description' => 'required|string|max:255',
            'receipt_file' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Data pengajuan tidak valid',
                'data' => $validator->errors()
            ], 422);
        }

        /** @var \App\Models\User $user */
        $user = $request->user();

        $activeFiscalYear = \App\Models\FiscalYear::where('is_active', true)->first();
        if (!$activeFiscalYear) {
            return response()->json(['success' => false, 'message' => 'Tidak ada Tahun Anggaran aktif.'], 400);
        }

        $budget = \App\Models\Budget::where('division_id', $user->division_id)
                                    ->where('fiscal_year_id', $activeFiscalYear->id)
                                    ->first();

        if (!$budget) {
            return response()->json(['success' => false, 'message' => 'Pagu anggaran divisi Anda belum diatur.'], 400);
        }

        $receiptPath = null;
        if ($request->hasFile('receipt_file')) {
            $receiptPath = $request->file('receipt_file')->store('receipts', 'public');
        }

        $reimbursement = \App\Models\Reimbursement::create([
            'user_id' => $user->id,
            'division_id' => $user->division_id,
            'fiscal_year_id' => $activeFiscalYear->id,
            'budget_id' => $budget->id,
            'title' => $request->title,
            'date' => $request->date,
            'amount' => $request->amount,
            'description' => $request->description,
            'receipt_path' => $receiptPath,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pengajuan dana berhasil dikirim',
            'data' => $reimbursement
        ], 201);
    }

    public function updateStatus(Request $request, $id)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();

        if (!$user->hasAnyRole(['manager', 'admin'])) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
        }

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'status' => 'required|in:approved,rejected',
            'rejection_reason' => 'required_if:status,rejected|string|max:255|nullable'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Data tidak valid', 'data' => $validator->errors()], 422);
        }

        $reimbursement = \App\Models\Reimbursement::find($id);

        if (!$reimbursement) {
            return response()->json(['success' => false, 'message' => 'Data pengajuan tidak ditemukan'], 404);
        }

        if ($reimbursement->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Pengajuan ini sudah diproses.'], 400);
        }

        try {
            \Illuminate\Support\Facades\DB::beginTransaction();

            if ($request->status === 'approved') {
                $budget = \App\Models\Budget::where('id', $reimbursement->budget_id)
                                            ->lockForUpdate()
                                            ->first();

                $remainingBalance = $budget->total_amount - $budget->used_amount;

                if ($remainingBalance < $reimbursement->amount) {
                    \Illuminate\Support\Facades\DB::rollBack();
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

            \Illuminate\Support\Facades\DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Status pengajuan berhasil diperbarui menjadi ' . strtoupper($request->status),
                'data' => $reimbursement
            ], 200);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada sistem saat memproses pengajuan.'
            ], 500);
        }
    }
}
