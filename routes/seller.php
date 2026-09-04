<?php

use App\Http\Controllers\Seller\ConversationController;
use App\Http\Controllers\Seller\DashboardController;
use App\Http\Controllers\Seller\OrderController;
use App\Http\Controllers\Seller\ProductController;
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

        Route::view('/revenus', 'seller.simple', [
            'title' => __('Revenus'),
            'description' => __('Suivi financier et revenus générés.'),
        ])->name('revenues');

        Route::view('/statistiques', 'seller.simple', [
            'title' => __('Statistiques'),
            'description' => __('Analyse des ventes et performances.'),
        ])->name('statistics');

        Route::view('/premium', 'seller.simple', [
            'title' => __('Premium'),
            'description' => __('Boostez votre visibilité avec Premium.'),
        ])->name('premium');

        Route::view('/parametres', 'seller.simple', [
            'title' => __('Paramètres'),
            'description' => __('Configurez votre compte vendeur.'),
        ])->name('settings');
    });
