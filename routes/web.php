<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SellerSubscriptionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/produits', [ProductController::class, 'index'])->name('products.index');
Route::get('/produits/{product}', [ProductController::class, 'show'])->name('products.show');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/devenir-vendeur', [SellerSubscriptionController::class, 'show'])->name('seller-subscription.show');
    Route::post('/devenir-vendeur', [SellerSubscriptionController::class, 'store'])->name('seller-subscription.store');

    Route::get('/panier', [CartController::class, 'show'])->name('cart.show');
    Route::post('/panier/ajouter/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::patch('/panier/{product}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/panier/{product}', [CartController::class, 'remove'])->name('cart.remove');
    Route::post('/panier/commander', [CartController::class, 'checkout'])->name('cart.checkout');

    Route::get('/mes-commandes', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/mes-commandes/{order}', [OrderController::class, 'show'])->name('orders.show');
});

require __DIR__.'/auth.php';
