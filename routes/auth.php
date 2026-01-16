<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'guest'], function () {
    Route::get('/auth/login', [LoginController::class, 'index']);
    Route::post('/auth/login', [LoginController::class, 'store'])->name('login');
    e('register');
});

Route::group(['middleware' => 'auth'], function () {
    Route::post('/auth/logout', [LoginController::class, 'destroy'])->name('logout');
});
