<?php

use App\Http\Controllers\Api\AuthController;
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
    Route::get('/reimbursements/pending', [ReimbursementController::class, 'pendingList']);
    Route::post('/reimbursements', [ReimbursementController::class, 'store']);
    Route::put('/reimbursements/{id}/status', [ReimbursementController::class, 'updateStatus']);

    Route::put('/profile', [ProfileController::class, 'update']);

    Route::post('/logout', [AuthController::class, 'logout']);
});
