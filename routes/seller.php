<?php

use App\Http\Controllers\Seller\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'seller.active'])
    ->prefix('seller')
    ->name('seller.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    });
