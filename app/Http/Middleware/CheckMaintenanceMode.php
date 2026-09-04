<?php

namespace App\Http\Middleware;

use App\Models\Setting\Setting;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $settings = Setting::where('status', 1)->pluck('value', 'key')->toArray();

        if ($settings['maintenance_mode'] == 0) {
            return $next($request);
        }
        $user = Auth::user();

        if ($user) {
            if ($user->hasRole('admin') || $user->is_owner) {
                return $next($request);
            }
        }

        abort(503, 'The site is being updated.');
    }
}
