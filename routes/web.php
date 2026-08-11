<?php

use App\Http\Controllers\Auth\GoogleSignInController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/auth/google/redirect', [GoogleSignInController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleSignInController::class, 'callback'])->name('google.callback');
