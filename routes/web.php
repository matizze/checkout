<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => Auth::check() ? redirect()->route('clients.index') : redirect()->route('login'));

Route::group(['middleware' => 'auth'], function () {});

require __DIR__.'/auth.php';
