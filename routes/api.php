<?php

use App\Http\Controllers\Api\Auth\LoginRegisterController;
use App\Http\Controllers\Api\Customer\SalesProcess\CartController;
use Illuminate\Support\Facades\Route;


Route::middleware('guest')->group(function () {

    Route::post('/login-register', [LoginRegisterController::class, 'loginRegisterStore'])->middleware('throttle:login-register-limiter');

    Route::post('/login-confirm/{token}', [LoginRegisterController::class, 'loginConfirmStore'])->middleware('throttle:login-confirm-limiter');

    Route::get('/login-resend-otp/{token}', [LoginRegisterController::class, 'resendOtp'])->middleware('throttle:login-resend-otp-limiter');
});

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/logout', [LoginRegisterController::class, 'logout']);

    //cart
    Route::get('/shoping-cart', [CartController::class, 'shopingCart']);
    Route::post('/add-to-cart', [CartController::class, 'addToCart'])->middleware('throttle:cart');
    
    // Route::get('/remove-from-cart/{cartItem}', [CartController::class, 'removeFromCart'])->name('customer.sales-process.remove-from-cart')->middleware('throttle:cart');
    // Route::post('/shoping-cart/update', [CartController::class, 'updateCart'])->name('customer.sales-process.update-shoping-cart');
    // Route::post('/shoping-cart/coupon', [CartController::class, 'coupon'])->name('customer.sales-process.coupon')->middleware('throttle:coupon');

    //address
    // Route::get('/address-and-delivery', [AddressController::class, 'addressAndDelivery'])->name('customer.sales-process.address-and-delivery');
    // Route::post('/store-address', [AddressController::class, 'storeAddress'])->name('customer.sales-process.store-address')->middleware('throttle:address');
    // Route::put('/update-address/{address}', [AddressController::class, 'updateAddress'])->name('customer.sales-process.update-address')->middleware('throttle:address');
    // Route::get('/provinces/{province}/cities', [AddressController::class, 'getCities']);
});
