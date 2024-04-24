<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderCompletedController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('checkout', [CheckoutController::class, 'create']);
Route::post('checkout', [CheckoutController::class, 'store']);

Route::get('checkout/completed', OrderCompletedController::class);
