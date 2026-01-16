<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => Auth::check() ? redirect()->route('products.index') : redirect()->route('login'));

Route::group(['middleware' => 'auth'], function () {
    Route::resource('products', ProductController::class);
});

require __DIR__.'/auth.php';
