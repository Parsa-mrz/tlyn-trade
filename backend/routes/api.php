<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Order\OrderController;
use App\Http\Controllers\Wallet\WalletController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('auth')->group(function () {
    Route::post('login', [LoginController::class, 'login']);
    Route::post('register', [RegisterController::class, 'register']);
});


Route::middleware('auth:sanctum')->group(function () {
   Route::post ('orders', [OrderController::class, 'store']);
   Route::get('orders', [OrderController::class, 'index']);
   Route::post('orders/{order}/cancel', [OrderController::class, 'cancel']);
   Route::get('wallet/{user}',[WalletController::class,'show']);
});
