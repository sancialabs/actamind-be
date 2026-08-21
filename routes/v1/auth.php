<?php

use App\Http\Controllers\Auth\ExchangeController;

Route::post('/auth/exchange', ExchangeController::class)->name('auth.exchange');
