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
    Route::middleware('role:Administrator')->group(function () {
    // Roles
    Route::resource('roles', \App\Http\Controllers\RoleController::class)->except(['create', 'edit', 'show']);

    // Menus
    Route::resource('menus', \App\Http\Controllers\MenuController::class)->except(['create', 'edit', 'show']);

    // Promos
    Route::resource('promos', \App\Http\Controllers\PromoController::class)->except(['create', 'edit', 'show']);

    // Web Settings
    Route::get('/web-settings', [\App\Http\Controllers\WebSettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [\App\Http\Controllers\WebSettingController::class, 'update'])->name('settings.update');

    // Financial Settings
    Route::prefix('financial')->name('financial.')->group(function () {
        Route::get('/tax', [\App\Http\Controllers\TaxController::class, 'index'])->name('tax.index');
        Route::put('/tax', [\App\Http\Controllers\TaxController::class, 'update'])->name('tax.update');
        Route::resource('payment-methods', \App\Http\Controllers\PaymentMethodController::class)->except(['create', 'edit', 'show']);
    });
    });

    // Operator & Administrator
    Route::middleware('role:Operator|Administrator')->group(function () {
        Route::get('transactions/{transaction}/print', [\App\Http\Controllers\Transaction\OrderController::class, 'print'])->name('transactions.print');
        Route::resource('transactions', \App\Http\Controllers\Transaction\OrderController::class)->except(['create', 'edit', 'update', 'destroy']);
        Route::get('pickups', [\App\Http\Controllers\Transaction\PickupController::class, 'index'])->name('pickups.index');
        Route::post('pickups/{id}', [\App\Http\Controllers\Transaction\PickupController::class, 'store'])->name('pickups.store');
    });

    // Pimpinan & Administrator
    Route::middleware('role:Pimpinan|Administrator')->group(function () {
        Route::get('reports', [\App\Http\Controllers\ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/print', [\App\Http\Controllers\ReportController::class, 'print'])->name('reports.print');
    });
});
