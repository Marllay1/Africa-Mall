<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureSellerActive
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->isSellerActive()) {
            if ($request->expectsJson() || $request->is('api/*')) {
                abort(403, 'seller_not_active');
            }

            return redirect()->route('seller-subscription.show');
        }

        return $next($request);
    }
}
