<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\DivisionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/login', [AuthController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rute Super Admin
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
})->middleware(['auth', 'role:admin']);
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
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

    Route::get('/logs', [ActivityLogController::class, 'index'])->name('admin.logs.index');
});

// Rute Manajer
Route::get('/manager/dashboard', function () {
    return view('manager.dashboard');
})->middleware(['auth', 'role:manager']);

// Rute  Staff
Route::get('/staff/dashboard', function () {
    return view('staff.dashboard');
})->middleware(['auth', 'role:staff']);
