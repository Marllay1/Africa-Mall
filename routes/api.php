<?php

use App\Http\Controllers\Api\Admin\PremiumRequestController as AdminPremiumRequestController;
use App\Http\Controllers\Api\Admin\SellerRequestController as AdminSellerRequestController;
use App\Http\Controllers\Api\Admin\ShopController as AdminShopController;
use App\Http\Controllers\Api\Admin\UserController as AdminUserController;
use App\Http\Controllers\Api\Admin\WithdrawalRequestController as AdminWithdrawalRequestController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ConversationController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\ReviewController;
use App\Http\Controllers\Api\Seller\ConversationController as SellerConversationController;
use App\Http\Controllers\Api\Seller\FinanceController as SellerFinanceController;
use App\Http\Controllers\Api\Seller\NotificationController as SellerNotificationController;
use App\Http\Controllers\Api\Seller\OrderController as SellerOrderController;
use App\Http\Controllers\Api\Seller\PremiumController as SellerPremiumController;
use App\Http\Controllers\Api\Seller\ProductController as SellerProductController;
use App\Http\Controllers\Api\Seller\SettingsController as SellerSettingsController;
use App\Http\Controllers\Api\SellerProfileController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::get('/seller/profile', [SellerProfileController::class, 'show']);
    Route::post('/seller/apply', [SellerProfileController::class, 'store']);

    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{order}', [OrderController::class, 'show']);

    // Customer
    Route::post('/favorites/{product}', [FavoriteController::class, 'toggle']);
    Route::post('/products/{product}/reviews', [ReviewController::class, 'store']);

    Route::get('/conversations', [ConversationController::class, 'index']);
    Route::post('/conversations/start/{product}', [ConversationController::class, 'startFromProduct']);
    Route::get('/conversations/{conversation}', [ConversationController::class, 'show']);
    Route::post('/conversations/{conversation}/envoyer', [ConversationController::class, 'send']);

    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead']);

    Route::get('/profile', [ProfileController::class, 'show']);
    Route::patch('/profile', [ProfileController::class, 'update']);
    Route::delete('/profile', [ProfileController::class, 'destroy']);

    // Seller (requires an active seller profile)
    Route::middleware('seller.active')->prefix('seller')->group(function () {
        Route::get('/products', [SellerProductController::class, 'index']);
        Route::post('/products', [SellerProductController::class, 'store']);
        Route::patch('/products/{product}', [SellerProductController::class, 'update']);
        Route::delete('/products/{product}', [SellerProductController::class, 'destroy']);

        Route::get('/orders', [SellerOrderController::class, 'index']);
        Route::patch('/orders/{order}/status', [SellerOrderController::class, 'updateStatus']);

        Route::get('/messages', [SellerConversationController::class, 'index']);
        Route::get('/messages/{conversation}', [SellerConversationController::class, 'show']);
        Route::post('/messages/{conversation}/envoyer', [SellerConversationController::class, 'send']);

        Route::get('/notifications', [SellerNotificationController::class, 'index']);
        Route::post('/notifications/{notification}/read', [SellerNotificationController::class, 'markRead']);
        Route::get('/notifications-badge', [SellerNotificationController::class, 'badge']);

        Route::get('/finances/revenues', [SellerFinanceController::class, 'revenues']);
        Route::get('/finances/statistics', [SellerFinanceController::class, 'statistics']);
        Route::post('/withdrawals', [SellerFinanceController::class, 'requestWithdrawal']);

        Route::get('/premium', [SellerPremiumController::class, 'show']);
        Route::post('/premium', [SellerPremiumController::class, 'store']);

        Route::get('/settings', [SellerSettingsController::class, 'show']);
        Route::patch('/settings', [SellerSettingsController::class, 'update']);
    });

    // Admin
    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/seller-requests', [AdminSellerRequestController::class, 'index']);
        Route::post('/seller-requests/{sellerProfile}/approve', [AdminSellerRequestController::class, 'approve']);
        Route::post('/seller-requests/{sellerProfile}/reject', [AdminSellerRequestController::class, 'reject']);

        Route::get('/premium-requests', [AdminPremiumRequestController::class, 'index']);
        Route::post('/premium-requests/{premiumSubscription}/approve', [AdminPremiumRequestController::class, 'approve']);
        Route::post('/premium-requests/{premiumSubscription}/reject', [AdminPremiumRequestController::class, 'reject']);

        Route::get('/withdrawal-requests', [AdminWithdrawalRequestController::class, 'index']);
        Route::post('/withdrawal-requests/{withdrawal}/approve', [AdminWithdrawalRequestController::class, 'approve']);
        Route::post('/withdrawal-requests/{withdrawal}/reject', [AdminWithdrawalRequestController::class, 'reject']);

        Route::get('/users', [AdminUserController::class, 'index']);
        Route::get('/shops', [AdminShopController::class, 'index']);
    });
});
