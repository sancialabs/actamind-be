<?php

use App\Http\Controllers\PlansController;
use Illuminate\Support\Facades\Route;

Route::prefix('plans')
    ->name('plans.')
    ->controller(PlansController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/{plan:uuid}', 'show')->name('show');
        Route::match(['put', 'patch'], '/{plan:uuid}', 'update')->name('update');
        Route::delete('/{plan:uuid}', 'destroy')->name('destroy');
    });
