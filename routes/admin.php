<?php

use App\Http\Controllers\Admin\SellerRequestController;
use App\Http\Controllers\Admin\ShopController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [SellerRequestController::class, 'index'])->name('dashboard');
        Route::get('/seller-requests', [SellerRequestController::class, 'index'])->name('seller-requests.index');
        Route::post('/seller-requests/{sellerProfile}/approve', [SellerRequestController::class, 'approve'])->name('seller-requests.approve');
        Route::post('/seller-requests/{sellerProfile}/reject', [SellerRequestController::class, 'reject'])->name('seller-requests.reject');

        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/shops', [ShopController::class, 'index'])->name('shops.index');
    });
