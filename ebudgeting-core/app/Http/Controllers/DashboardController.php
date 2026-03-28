<?php

namespace App\Http\Controllers;

use App\Models\Reimbursement;
use App\Models\Budget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Models\Activity;

class DashboardController extends Controller
{
    public function admin()
    {
        $pendingCount = Reimbursement::where('status', 'pending')->count();

        $approvedThisMonth = Reimbursement::where('status', 'approved')
            ->whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->sum('amount');

        $totalBudgetRemaining = Budget::whereHas('fiscalYear', function ($q) {
            $q->where('is_active', true);
        })->sum(DB::raw('total_amount - used_amount'));

        $recentReimbursements = Reimbursement::with('user.division')->latest()->take(5)->get();
        $recentLogs = Activity::with('causer')->latest()->take(5)->get();

        $monthlyData = Reimbursement::select(
            DB::raw('EXTRACT(MONTH FROM updated_at) as month'),
            DB::raw('SUM(amount) as total')
        )
            ->where('status', 'approved')
            ->whereYear('updated_at', date('Y'))
            ->groupBy(DB::raw('EXTRACT(MONTH FROM updated_at)'))
            ->orderBy('month')
            ->get();

        $chartData = array_fill(0, 12, 0);
        foreach ($monthlyData as $data) {
            $monthIndex = (int) $data->month - 1;
            $chartData[$monthIndex] = (int) $data->total;
        }
        $chartDataJson = json_encode($chartData);

        return view('admin.dashboard', compact(
            'pendingCount',
            'approvedThisMonth',
            'totalBudgetRemaining',
            'recentReimbursements',
            'recentLogs',
            'chartDataJson'
        ));
    }

    public function manager()
    {
        $user = Auth::user();

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
            ->whereHas('fiscalYear', function ($q) {
                $q->where('is_active', true);
            })->sum(DB::raw('total_amount - used_amount'));

        $recentReimbursements = Reimbursement::with('user.division')
            ->whereHas('user', function ($q) use ($managedDivisionIds) {
                $q->whereIn('division_id', $managedDivisionIds);
            })->latest()->take(5)->get();

        return view('manager.dashboard', compact('pendingCount', 'approvedThisMonth', 'totalBudgetRemaining', 'recentReimbursements'));
    }

    public function staff()
    {
        $user = Auth::user();

        $myPending = Reimbursement::where('user_id', $user->id)->where('status', 'pending')->count();
        $myApprovedTotal = Reimbursement::where('user_id', $user->id)->where('status', 'approved')->sum('amount');

        $myBudgets = Budget::where('division_id', $user->division_id)
            ->whereHas('fiscalYear', function ($q) {
                $q->where('is_active', true);
            })->get();

        $recentReimbursements = Reimbursement::with('budget')->where('user_id', $user->id)->latest()->take(5)->get();

        return view('staff.dashboard', compact('myPending', 'myApprovedTotal', 'myBudgets', 'recentReimbursements'));
    }
}
