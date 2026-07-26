<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\LoginRegisterController;
use App\Http\Controllers\Auth\VerifyEmailController;
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
Route::get('/logout', [LoginRegisterController::class, 'logout'])
    ->name('logout');
// Route::middleware('auth')->group(function () {

    //     Route::get('verify-email', EmailVerificationPromptController::class)
    //         ->name('verification.notice');

    //     Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
    //         ->middleware(['signed', 'throttle:6,1'])
    //         ->name('verification.verify');

    //     Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    //         ->middleware('throttle:6,1')
    //         ->name('verification.send');

    //     Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
    //         ->name('password.confirm');

    //     Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

    //     Route::put('password', [PasswordController::class, 'update'])->name('password.update');


// });
