<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FiscalYear;
use App\Models\Budget;
use App\Models\Reimbursement;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $division = $user->division;

        $activeFiscalYear = FiscalYear::where('is_active', true)->first();

        $budgetInfo = null;
        if ($division && $activeFiscalYear) {
            $budgetInfo = Budget::where('division_id', $division->id)
                ->where('fiscal_year_id', $activeFiscalYear->id)
                ->first();
        }

        $recentReimbursements = Reimbursement::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Data dasbor berhasil dimuat',
            'data' => [
                'profile' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->roles->first()->name ?? 'staff',
                    'division' => $division ? $division->name : 'Tidak ada divisi',
                ],
                'fiscal_year' => $activeFiscalYear ? $activeFiscalYear->year : 'Tidak ada tahun aktif',
                'budget' => [
                    'total_amount' => $budgetInfo ? $budgetInfo->total_amount : 0,
                    'used_amount' => $budgetInfo ? $budgetInfo->used_amount : 0,
                    'remaining_amount' => $budgetInfo ? ($budgetInfo->total_amount - $budgetInfo->used_amount) : 0,
                ],
                'recent_history' => $recentReimbursements
            ]
        ], 200);
    }
}
