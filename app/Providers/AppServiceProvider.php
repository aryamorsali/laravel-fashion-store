<?php

namespace App\Providers;

use App\Models\Market\CartItem;
use App\Models\Market\ProductCategory;
use App\Models\Setting\Setting;
use App\Models\User;
use App\Models\User\Permission;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
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
        RateLimiter::for('login-register-limiter', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        RateLimiter::for('login-confirm-limiter', function (Request $request) {
            return Limit::perMinute(4)->by($request->ip());
        });

        RateLimiter::for('login-resend-otp-limiter', function (Request $request) {
            return Limit::perMinute(3)->by($request->ip());
        });

        RateLimiter::for('add-comment', function (Request $request) {
            return Limit::perMinute(10)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('cart', function (Request $request) {
            return Limit::perMinute(30)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('coupon', function (Request $request) {
            return Limit::perMinute(5)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('address', function (Request $request) {
            return Limit::perMinute(5)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('payment', function (Request $request) {
            return Limit::perMinute(10)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('like', function (Request $request) {
            return Limit::perMinute(30)->by($request->user()?->id ?: $request->ip());
        });

        RateLimiter::for('payment-callback', function (Request $request) {
            return Limit::perMinute(30)->by($request->ip());
        });

        // برای هدر این مقادیر ارسال میشود
        view()->composer('customer.layouts.header', function ($view) {
            if (Auth::check()) {
                $view->with('cartItems', CartItem::where('user_id', Auth::user()->id)->get());
            }
        });

        // Gates
        Gate::before(function (User $user) {
            if ($user->is_owner) return true;
            return null; // برو ادامه بده
        });

        $permissions = Permission::all()->pluck('name');

        foreach ($permissions as $permission) {
            Gate::define($permission, function (User $user) use ($permission) {
                return $user->hasPermissionTo($permission);
            });
        }


        // این متغیرها فقط و فقط به فایل layouts.app و فایل‌های داخل پوشه customer فرستاده می‌شوند
        View::composer(['customer.layouts.app', 'customer.pages', 'customer.pages.contact'], function ($view) {

            // array
            $settings = Setting::where('status', 1)->pluck('value', 'key')->toArray();

            $categories = ProductCategory::where('parent_id', null)->where('status', 1)->get();

            $view->with([
                'settings' => $settings,
                'categories' => $categories,
            ]);
        });
    }
}
