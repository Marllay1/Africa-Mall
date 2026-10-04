<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PremiumRequestController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SellerRequestController;
use App\Http\Controllers\Admin\ShopController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WithdrawalRequestController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/seller-requests', [SellerRequestController::class, 'index'])->name('seller-requests.index');
        Route::post('/seller-requests/{sellerProfile}/approve', [SellerRequestController::class, 'approve'])->name('seller-requests.approve');
        Route::post('/seller-requests/{sellerProfile}/reject', [SellerRequestController::class, 'reject'])->name('seller-requests.reject');
        Route::post('/seller-requests/{sellerProfile}/suspend', [SellerRequestController::class, 'suspend'])->name('seller-requests.suspend');
        Route::post('/seller-requests/{sellerProfile}/reactivate', [SellerRequestController::class, 'reactivate'])->name('seller-requests.reactivate');

        Route::get('/premium-requests', [PremiumRequestController::class, 'index'])->name('premium-requests.index');
        Route::post('/premium-requests/{premiumSubscription}/approve', [PremiumRequestController::class, 'approve'])->name('premium-requests.approve');
        Route::post('/premium-requests/{premiumSubscription}/reject', [PremiumRequestController::class, 'reject'])->name('premium-requests.reject');

        Route::get('/withdrawal-requests', [WithdrawalRequestController::class, 'index'])->name('withdrawal-requests.index');
        Route::post('/withdrawal-requests/{withdrawal}/approve', [WithdrawalRequestController::class, 'approve'])->name('withdrawal-requests.approve');
        Route::post('/withdrawal-requests/{withdrawal}/reject', [WithdrawalRequestController::class, 'reject'])->name('withdrawal-requests.reject');

        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/shops', [ShopController::class, 'index'])->name('shops.index');

        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::patch('/products/{product}/visibility', [ProductController::class, 'toggleVisibility'])->name('products.toggle-visibility');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');

        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
    });
