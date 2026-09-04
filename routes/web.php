<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SellerSubscriptionController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/produits');

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
    Route::get('/panier/paiement', [CartController::class, 'showPayment'])->name('cart.payment');
    Route::post('/panier/commander', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::post('/panier/acheter-maintenant/{product}', [CartController::class, 'buyNow'])->name('cart.buy-now');

    Route::get('/mes-commandes', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/mes-commandes/{order}', [OrderController::class, 'show'])->name('orders.show');

    Route::post('/produits/{product}/favoris', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::post('/produits/{product}/avis', [ReviewController::class, 'store'])->name('reviews.store');
    Route::post('/produits/{product}/contacter', [ConversationController::class, 'startFromProduct'])->name('conversations.start');

    Route::get('/mes-messages', [ConversationController::class, 'index'])->name('conversations.index');
    Route::get('/mes-messages-badge', [ConversationController::class, 'badge'])->name('conversations.badge');
    Route::get('/mes-messages/{conversation}', [ConversationController::class, 'show'])->name('conversations.show');
    Route::post('/mes-messages/{conversation}/envoyer', [ConversationController::class, 'send'])->name('conversations.send');
    Route::get('/mes-messages/{conversation}/nouveaux', [ConversationController::class, 'poll'])->name('conversations.poll');
});

require __DIR__.'/auth.php';
