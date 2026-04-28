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
        $role = $user->roles->first()->name ?? 'staff';

        $activeFiscalYear = FiscalYear::where('is_active', true)->first();

        if ($role === 'manager' || $user->hasRole('manager')) {
            $managedDivisionIds = $user->managedDivisions->pluck('id')->toArray();
            
            $pendingCount = Reimbursement::whereHas('user', function ($q) use ($managedDivisionIds) {
                $q->whereIn('division_id', $managedDivisionIds);
            })->where('status', 'pending')->count();
            
            $approvedThisMonth = Reimbursement::whereHas('user', function ($q) use ($managedDivisionIds) {
                $q->whereIn('division_id', $managedDivisionIds);
            })->where('status', 'approved')
                ->whereMonth('updated_at', now()->month)
                ->whereYear('updated_at', now()->year)
                ->sum('amount');
                
            $totalBudgetRemaining = Budget::whereIn('division_id', $managedDivisionIds)
                ->whereDate('end_date', '>=', now()->format('Y-m-d'))
                ->whereHas('fiscalYear', function ($q) {
                    $q->where('is_active', true);
                })->sum(\Illuminate\Support\Facades\DB::raw('total_amount - used_amount'));
                
            $recentReimbursements = Reimbursement::with('user.division')
                ->whereHas('user', function ($q) use ($managedDivisionIds) {
                    $q->whereIn('division_id', $managedDivisionIds);
                })->latest()->take(5)->get();

            return response()->json([
                'success' => true,
                'message' => 'Data dasbor manager berhasil dimuat',
                'data' => [
                    'profile' => [
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => 'manager',
                        'division' => $division ? $division->name : 'Tidak ada divisi',
                    ],
                    'fiscal_year' => $activeFiscalYear ? $activeFiscalYear->year : 'Tidak ada tahun aktif',
                    'stats' => [
                        'pending_count' => $pendingCount,
                        'approved_this_month' => $approvedThisMonth,
                        'total_budget_remaining' => $totalBudgetRemaining,
                    ],
                    'recent_history' => $recentReimbursements
                ]
            ], 200);
        }

        // Default Staff Logic
        $budgets = collect();
        $budgetSummary = ['total_amount' => 0, 'used_amount' => 0, 'remaining_amount' => 0];

        if ($division && $activeFiscalYear) {
            $budgets = Budget::with('budgetCategory')
                ->where('division_id', $division->id)
                ->where('fiscal_year_id', $activeFiscalYear->id)
                ->whereDate('end_date', '>=', now()->format('Y-m-d'))
                ->whereHas('fiscalYear', function ($q) {
                    $q->whereDate('end_date', '>=', now()->format('Y-m-d'))
                      ->where('is_active', true);
                })
                ->whereRaw('total_amount > used_amount')
                ->get();

            $budgetSummary = [
                'total_amount' => $budgets->sum('total_amount'),
                'used_amount' => $budgets->sum('used_amount'),
                'remaining_amount' => $budgets->sum('total_amount') - $budgets->sum('used_amount'),
            ];
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
                    'role' => 'staff',
                    'division' => $division ? $division->name : 'Tidak ada divisi',
                ],
                'fiscal_year' => $activeFiscalYear ? $activeFiscalYear->year : 'Tidak ada tahun aktif',
                'budget' => $budgetSummary,
                'available_budgets' => $budgets->map(function ($b) {
                    return [
                        'id' => $b->id,
                        'name' => $b->name,
                        'category' => $b->budgetCategory ? $b->budgetCategory->name : '-',
                        'total_amount' => $b->total_amount,
                        'used_amount' => $b->used_amount,
                        'remaining' => $b->total_amount - $b->used_amount,
                    ];
                }),
                'recent_history' => $recentReimbursements
            ]
        ], 200);
    }
}
