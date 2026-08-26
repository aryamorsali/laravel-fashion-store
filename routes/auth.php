<?php

use App\Http\Controllers\Auth\LoginRegisterController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('login-register', [LoginRegisterController::class, 'loginRegisterForm'])
        ->name('auth.login-register.form');

    Route::post('/login-register', [LoginRegisterController::class, 'loginRegisterStore'])->middleware('throttle:login-register-limiter')->name('auth.login-register.store');

    Route::get('/login-confirm/{token}', [LoginRegisterController::class, 'loginConfirmForm'])
        ->name('auth.login-confirm.form');

    Route::post('/login-confirm/{token}', [LoginRegisterController::class, 'loginConfirmStore'])->middleware('throttle:login-confirm-limiter')->name('auth.login-confirm.store');

    Route::get('/login-resend-otp/{token}', [LoginRegisterController::class, 'resendOtp'])->middleware('throttle:login-resend-otp-limiter')
        ->name('auth.login-resend-otp');
});

Route::get('/logout', [LoginRegisterController::class, 'logout'])->name('logout')->middleware('auth');
    
