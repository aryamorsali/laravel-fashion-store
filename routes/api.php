<?php

use App\Http\Controllers\Api\Auth\LoginRegisterController;
use Illuminate\Support\Facades\Route;


Route::middleware('guest')->group(function () {

    Route::post('/login-register', [LoginRegisterController::class, 'loginRegisterStore'])->middleware('throttle:login-register-limiter');

    Route::post('/login-confirm/{token}', [LoginRegisterController::class, 'loginConfirmStore'])->middleware('throttle:login-confirm-limiter');

    Route::get('/login-resend-otp/{token}', [LoginRegisterController::class, 'resendOtp'])->middleware('throttle:login-resend-otp-limiter');
});

Route::get('/logout', [LoginRegisterController::class, 'logout'])->middleware('auth:sanctum');
