<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\BudgetCategoryController;
use App\Http\Controllers\Admin\DivisionController;
use App\Http\Controllers\Admin\FiscalYearController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReimbursementController;
use App\Http\Controllers\Admin\AnnualReportController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Route::get('/restdb-77-4458-sarmat-ZWCWfO0y26c', function() {
//     \Illuminate\Support\Facades\Artisan::call('migrate:fresh', [
//         '--seed' => true,
//         '--force' => true
//     ]);
//     return 'Mantap! Database berhasil di-reset bersih dan di-seed ulang!';
// });

Route::get('/', function () {
    if (Auth::check()) {

        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return redirect('/admin/dashboard');
        } elseif ($user->hasRole('manager')) {
            return redirect('/manager/dashboard');
        } else {
            return redirect('/reimbursements');
        }
    }

    return view('index');
});

Route::get('/login', [AuthController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rute Super Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');

    Route::get('/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::post('/users', [UserController::class, 'store'])->name('admin.users.store');
    Route::get('/users/template', [UserController::class, 'template'])->name('admin.users.template');
    Route::post('/users/import', [UserController::class, 'import'])->name('admin.users.import');
    Route::put('/users/{id}', [UserController::class, 'update'])->name('admin.users.update');
    Route::delete('/users/{id}', [UserController::class, 'destroy'])->name('admin.users.destroy');

    Route::get('/divisions', [DivisionController::class, 'index'])->name('admin.divisions.index');
    Route::post('/divisions', [DivisionController::class, 'store'])->name('admin.divisions.store');
    Route::put('/divisions/{id}', [DivisionController::class, 'update'])->name('admin.divisions.update');
    Route::delete('/divisions/{id}', [DivisionController::class, 'destroy'])->name('admin.divisions.destroy');

    Route::get('/fiscal-years', [FiscalYearController::class, 'index'])->name('admin.fiscal_years.index');
    Route::post('/fiscal-years', [FiscalYearController::class, 'store'])->name('admin.fiscal_years.store');
    Route::put('/fiscal-years/{id}', [FiscalYearController::class, 'update'])->name('admin.fiscal_years.update');
    Route::delete('/fiscal-years/{id}', [FiscalYearController::class, 'destroy'])->name('admin.fiscal_years.destroy');

    Route::get('/categories', [BudgetCategoryController::class, 'index'])->name('admin.categories.index');
    Route::post('/categories', [BudgetCategoryController::class, 'store'])->name('admin.categories.store');
    Route::put('/categories/{id}', [BudgetCategoryController::class, 'update'])->name('admin.categories.update');
    Route::delete('/categories/{id}', [BudgetCategoryController::class, 'destroy'])->name('admin.categories.destroy');

    Route::get('/annual-reports', [AnnualReportController::class, 'index'])->name('annual_reports.index');
    Route::get('/annual-reports/{id}/download', [AnnualReportController::class, 'download'])->name('annual_reports.download');

    Route::get('/logs', [ActivityLogController::class, 'index'])->name('admin.logs.index');
});

// Rute Manajer
Route::middleware(['auth', 'role:manager'])->prefix('manager')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'manager'])->name('manager.dashboard');
});

// Rute  Staff
Route::middleware(['auth', 'role:staff'])->prefix('staff')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'staff'])->name('staff.dashboard');
});

// Rute Admin|Manager untuk manajemen anggaran
Route::middleware(['auth', 'role:admin|manager'])->prefix('budgets')->group(function () {
    Route::get('/', [BudgetController::class, 'index'])->name('budgets.index');
    Route::post('/', [BudgetController::class, 'store'])->name('budgets.store');
    Route::put('/{id}', [BudgetController::class, 'update'])->name('budgets.update');
    Route::delete('/{id}', [BudgetController::class, 'destroy'])->name('budgets.destroy');
});

// Rute untuk pengajuan reimbursement (Admin & Staff bisa buat, Admin & Manager bisa approve/reject)
Route::middleware(['auth'])->prefix('reimbursements')->name('reimbursements.')->group(function () {
    Route::get('/', [ReimbursementController::class, 'index'])->name('index');
    Route::post('/', [ReimbursementController::class, 'store'])->name('store')->middleware('role:admin|staff');
    Route::put('/{id}/approve', [ReimbursementController::class, 'approve'])->name('approve')->middleware('role:admin|manager');
    Route::put('/{id}/reject', [ReimbursementController::class, 'reject'])->name('reject')->middleware('role:admin|manager');

    Route::get('/export-pdf', [ReimbursementController::class, 'exportPdf'])->name('export.pdf')->middleware('role:admin|manager');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});
