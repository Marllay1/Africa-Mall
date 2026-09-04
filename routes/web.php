<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SellerSubscriptionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/devenir-vendeur', [SellerSubscriptionController::class, 'show'])->name('seller-subscription.show');
    Route::post('/devenir-vendeur', [SellerSubscriptionController::class, 'store'])->name('seller-subscription.store');
});

require __DIR__.'/auth.php';
