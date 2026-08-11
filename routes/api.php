<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/auth/exchange', function (Request $request) {
    $request->validate(['code' => 'required|string']);

    $token = cache()->pull("oauth_code:{$request->code}");

    if (! $token) {
        return response()->json(['message' => 'Invalid or expired code'], 401);
    }

    return response()->json(['token' => $token]);
})->name('auth.exchange');

require __DIR__.'/v1/plans.php';
