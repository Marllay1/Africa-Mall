<?php

use App\Http\Controllers\Admin\AdvertisementController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\CustomerPremiumRequestController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DisputeController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\PremiumRequestController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\SellerRequestController;
use App\Http\Controllers\Admin\SettingsController;
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

        Route::get('/customer-premium-requests', [CustomerPremiumRequestController::class, 'index'])->name('customer-premium-requests.index');
        Route::post('/customer-premium-requests/{customerPremiumSubscription}/approve', [CustomerPremiumRequestController::class, 'approve'])->name('customer-premium-requests.approve');
        Route::post('/customer-premium-requests/{customerPremiumSubscription}/reject', [CustomerPremiumRequestController::class, 'reject'])->name('customer-premium-requests.reject');

        Route::get('/withdrawal-requests', [WithdrawalRequestController::class, 'index'])->name('withdrawal-requests.index');
        Route::post('/withdrawal-requests/{withdrawal}/approve', [WithdrawalRequestController::class, 'approve'])->name('withdrawal-requests.approve');
        Route::post('/withdrawal-requests/{withdrawal}/reject', [WithdrawalRequestController::class, 'reject'])->name('withdrawal-requests.reject');

        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users/{user}/block', [UserController::class, 'block'])->name('users.block');
        Route::post('/users/{user}/unblock', [UserController::class, 'unblock'])->name('users.unblock');
        Route::get('/shops', [ShopController::class, 'index'])->name('shops.index');

        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::patch('/products/{product}/visibility', [ProductController::class, 'toggleVisibility'])->name('products.toggle-visibility');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}/dispute', [DisputeController::class, 'forOrder'])->name('orders.dispute');

        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        Route::get('/disputes', [DisputeController::class, 'index'])->name('disputes.index');
        Route::get('/disputes/{dispute}', [DisputeController::class, 'show'])->name('disputes.show');
        Route::patch('/disputes/{dispute}/notes', [DisputeController::class, 'updateNotes'])->name('disputes.update-notes');
        Route::post('/disputes/{dispute}/resolve', [DisputeController::class, 'resolve'])->name('disputes.resolve');

        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::post('/reports/{report}/review', [ReportController::class, 'review'])->name('reports.review');

        Route::get('/payment-methods', [PaymentMethodController::class, 'index'])->name('payment-methods.index');
        Route::post('/payment-methods', [PaymentMethodController::class, 'store'])->name('payment-methods.store');
        Route::put('/payment-methods/{paymentMethod}', [PaymentMethodController::class, 'update'])->name('payment-methods.update');
        Route::patch('/payment-methods/{paymentMethod}/toggle', [PaymentMethodController::class, 'toggle'])->name('payment-methods.toggle');
        Route::delete('/payment-methods/{paymentMethod}', [PaymentMethodController::class, 'destroy'])->name('payment-methods.destroy');

        Route::get('/coupons', [CouponController::class, 'index'])->name('coupons.index');
        Route::post('/coupons', [CouponController::class, 'store'])->name('coupons.store');
        Route::patch('/coupons/{coupon}/toggle', [CouponController::class, 'toggle'])->name('coupons.toggle');
        Route::delete('/coupons/{coupon}', [CouponController::class, 'destroy'])->name('coupons.destroy');

        Route::get('/advertisements', [AdvertisementController::class, 'index'])->name('advertisements.index');
        Route::post('/advertisements/{advertisement}/approve', [AdvertisementController::class, 'approve'])->name('advertisements.approve');
        Route::post('/advertisements/{advertisement}/reject', [AdvertisementController::class, 'reject'])->name('advertisements.reject');

        Route::get('/parametres', [SettingsController::class, 'edit'])->name('settings.edit');
        Route::patch('/parametres', [SettingsController::class, 'update'])->name('settings.update');
        Route::post('/parametres/test-email', [SettingsController::class, 'sendTestEmail'])->name('settings.test-email');
        Route::get('/parametres/logs', [SettingsController::class, 'logs'])->name('settings.logs');
    });
