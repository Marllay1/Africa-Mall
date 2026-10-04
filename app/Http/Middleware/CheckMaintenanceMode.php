<?php

namespace App\Http\Middleware;

use App\Models\PlatformSetting;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckMaintenanceMode
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $settings = PlatformSetting::current();

        if (! $settings->maintenance_mode) {
            return $next($request);
        }

        if ($request->user()?->isAdmin() || $request->is('login', 'logout') || $request->routeIs('admin.*')) {
            return $next($request);
        }

        return response()->view('maintenance', ['message' => $settings->maintenance_message], 503);
    }
}
