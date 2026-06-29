<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;

Route::get('login', [AuthController::class, 'showLogin'])->name('login');
Route::post('login', [AuthController::class, 'authenticate']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');

    // Administrator
    Route::middleware('role:Administrator')->prefix('master')->name('master.')->group(function () {
        Route::resource('customers', \App\Http\Controllers\Master\CustomerController::class)->except('show');
        Route::resource('services', \App\Http\Controllers\Master\ServiceController::class)->except('show');
        Route::resource('users', \App\Http\Controllers\Master\UserController::class)->except('show');
    });

    // Operator & Administrator
    Route::middleware('role:Operator,Administrator')->group(function () {
        Route::get('transactions/{transaction}/print', [\App\Http\Controllers\Transaction\OrderController::class, 'print'])->name('transactions.print');
        Route::resource('transactions', \App\Http\Controllers\Transaction\OrderController::class)->except(['edit', 'update', 'destroy']);
        Route::get('pickups', [\App\Http\Controllers\Transaction\PickupController::class, 'index'])->name('pickups.index');
        Route::post('pickups/{id}', [\App\Http\Controllers\Transaction\PickupController::class, 'store'])->name('pickups.store');
    });

    // Pimpinan & Administrator
    Route::middleware('role:Pimpinan,Administrator')->group(function () {
        Route::get('reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/print', [\App\Http\Controllers\ReportController::class, 'print'])->name('reports.print');
    });
});
