<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('login-register-limiter', function ($request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        RateLimiter::for('login-confirm-limiter', function ($request) {
            return Limit::perMinute(4)->by($request->ip());
        });

        RateLimiter::for('login-resend-otp-limiter', function ($request) {
            return Limit::perMinute(3)->by($request->ip());
        });
    }
}
