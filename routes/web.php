<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SettingsController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => Auth::check() ? redirect()->route('products.index') : redirect()->route('login'));

// Checkout routes (public)
Route::prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/{product}', [CheckoutController::class, 'start'])->name('start');
    Route::get('/dados/cliente', [CheckoutController::class, 'customer'])->name('customer');
    Route::post('/dados/cliente', [CheckoutController::class, 'storeCustomer'])->name('customer.store');
    Route::get('/pagamento/pix', [CheckoutController::class, 'payment'])->name('payment');
    Route::get('/status/{payment}', [CheckoutController::class, 'status'])->name('status');
    Route::get('/status/{payment}/check', [CheckoutController::class, 'checkStatus'])->name('status.check');
});

Route::group(['middleware' => 'auth'], function () {
    // Settings routes
    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::patch('/settings/profile', [SettingsController::class, 'updateProfile'])->name('settings.profile.update');
    Route::patch('/settings/password', [SettingsController::class, 'updatePassword'])->name('settings.password.update');
    Route::delete('/settings/account', [SettingsController::class, 'destroy'])->name('settings.account.destroy');

    Route::resource('products', ProductController::class);
    Route::resource('orders', OrderController::class)->only(['index', 'show']);
});

require __DIR__.'/auth.php';
