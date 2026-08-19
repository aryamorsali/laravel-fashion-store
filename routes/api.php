<?php

use App\Http\Controllers\Api\Auth\LoginRegisterController;
use App\Http\Controllers\Api\Customer\Market\ProductController;
use App\Http\Controllers\Api\Customer\Market\ShopController;
use App\Http\Controllers\Api\Customer\SalesProcess\AddressController;
use App\Http\Controllers\Api\Customer\SalesProcess\CartController;
use App\Http\Controllers\LikeController;
use Illuminate\Support\Facades\Route;


Route::middleware('guest')->group(function () {

    Route::post('/login-register', [LoginRegisterController::class, 'loginRegisterStore'])->middleware('throttle:login-register-limiter');

    Route::post('/login-confirm/{token}', [LoginRegisterController::class, 'loginConfirmStore'])->middleware('throttle:login-confirm-limiter');

    Route::get('/login-resend-otp/{token}', [LoginRegisterController::class, 'resendOtp'])->middleware('throttle:login-resend-otp-limiter');
});



// product detail
Route::get('/product/{product:slug}', [ProductController::class, 'product']);



Route::middleware('auth:sanctum')->group(function () {

    Route::get('/logout', [LoginRegisterController::class, 'logout']);

    //cart
    Route::get('/shoping-cart', [CartController::class, 'shopingCart']);
    Route::post('/add-to-cart', [CartController::class, 'addToCart'])->middleware('throttle:cart');
    Route::get('/remove-from-cart/{cartItem}', [CartController::class, 'removeFromCart'])->middleware(['throttle:cart', 'can:delete,cartItem']);
    Route::post('/shoping-cart/update', [CartController::class, 'updateCart']);
    Route::post('/shoping-cart/coupon', [CartController::class, 'coupon'])->middleware('throttle:coupon');

    // like
    Route::post('/like/{type}/{id}', [LikeController::class, 'toggle'])->middleware('throttle:like');

    // product add comment
    Route::post('/product/{product:slug}/add-comment', [ProductController::class, 'addComment'])->middleware('throttle:add-comment');

    //address
    Route::get('/address-and-delivery', [AddressController::class, 'addressAndDelivery']);
    Route::post('/store-address', [AddressController::class, 'storeAddress'])->middleware('throttle:address');
    
    // Route::put('/update-address/{address}', [AddressController::class, 'updateAddress'])->middleware(['throttle:address', 'can:update,address']);
    // Route::get('/provinces/{province}/cities', [AddressController::class, 'getCities']);
});
