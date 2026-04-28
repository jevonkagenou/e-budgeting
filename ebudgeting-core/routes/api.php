<?php

use App\Http\Controllers\Api\ActivityLogController;
use App\Http\Controllers\Api\AnnualReportController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BudgetController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ReimbursementController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->name('mobile.login');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::get('/dashboard', [DashboardController::class, 'index']);

    Route::get('/reimbursements', [ReimbursementController::class, 'index']);
    Route::get('/reimbursements/manager', [ReimbursementController::class, 'managerList']);
    Route::get('/reimbursements/pending', [ReimbursementController::class, 'pendingList']);
    Route::post('/reimbursements', [ReimbursementController::class, 'store']);
    Route::put('/reimbursements/{id}/status', [ReimbursementController::class, 'updateStatus']);
    Route::delete('/reimbursements/{id}', [ReimbursementController::class, 'destroy']);
    Route::get('/reimbursements/export/pdf', [ReimbursementController::class, 'exportPdf']);

    Route::put('/profile', [ProfileController::class, 'update']);

    // Management routes (Manager)
    Route::get('/management/logs', [ActivityLogController::class, 'index']);
    Route::get('/management/annual-reports', [AnnualReportController::class, 'index']);
    Route::get('/management/annual-reports/{id}/download', [AnnualReportController::class, 'download']);

    // Budget routes (Manager)
    Route::get('/budgets/form-metadata', [BudgetController::class, 'formMetadata']);
    Route::get('/budgets', [BudgetController::class, 'index']);
    Route::post('/budgets', [BudgetController::class, 'store']);
    Route::put('/budgets/{id}', [BudgetController::class, 'update']);
    Route::delete('/budgets/{id}', [BudgetController::class, 'destroy']);

    Route::post('/logout', [AuthController::class, 'logout']);
});
