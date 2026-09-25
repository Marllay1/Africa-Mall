<?php

use App\Http\Controllers\Seller\ConversationController;
use App\Http\Controllers\Seller\DashboardController;
use App\Http\Controllers\Seller\FinanceController;
use App\Http\Controllers\Seller\NotificationController;
use App\Http\Controllers\Seller\OrderController;
use App\Http\Controllers\Seller\PremiumController;
use App\Http\Controllers\Seller\ProductController;
use App\Http\Controllers\Seller\SettingsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified', 'seller.active'])
    ->prefix('seller')
    ->name('seller.')
    ->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('products', ProductController::class)->except('show');

        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');

        Route::get('/messages', [ConversationController::class, 'index'])->name('conversations.index');
        Route::get('/messages-badge', [ConversationController::class, 'badge'])->name('conversations.badge');
        Route::get('/messages/{conversation}', [ConversationController::class, 'show'])->name('conversations.show');
        Route::post('/messages/{conversation}/envoyer', [ConversationController::class, 'send'])->name('conversations.send');
        Route::get('/messages/{conversation}/nouveaux', [ConversationController::class, 'poll'])->name('conversations.poll');

        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::get('/notifications-badge', [NotificationController::class, 'badge'])->name('notifications.badge');

        Route::get('/revenus', [FinanceController::class, 'revenues'])->name('revenues');
        Route::post('/retraits', [FinanceController::class, 'requestWithdrawal'])->name('withdrawals.store');

        Route::get('/statistiques', [FinanceController::class, 'statistics'])->name('statistics');

        Route::get('/premium', [PremiumController::class, 'show'])->name('premium');
        Route::post('/premium', [PremiumController::class, 'store'])->name('premium.store');

        Route::get('/parametres', [SettingsController::class, 'edit'])->name('settings');
        Route::patch('/parametres', [SettingsController::class, 'update'])->name('settings.update');
    });
