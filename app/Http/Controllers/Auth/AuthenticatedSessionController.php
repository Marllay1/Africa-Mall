<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the Customer login view.
     */
    public function create(): View
    {
        return view('auth.login', ['portal' => 'customer']);
    }

    /**
     * Handle an incoming Customer authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('products.index', absolute: false));
    }

    /**
     * Display the Seller Center login view.
     */
    public function createSeller(): View
    {
        return view('auth.login', ['portal' => 'seller']);
    }

    /**
     * Handle an incoming Seller authentication request. Any valid account may sign
     * in here — it routes active sellers straight to Seller Center and sends anyone
     * else to the "become a seller" application, matching their evident intent.
     */
    public function storeSeller(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        if ($request->user()->isSellerActive()) {
            return redirect()->intended(route('seller.dashboard', absolute: false));
        }

        return redirect()->route('seller-subscription.show');
    }

    /**
     * Display the Admin backoffice login view.
     */
    public function createAdmin(): View
    {
        return view('auth.login', ['portal' => 'admin']);
    }

    /**
     * Handle an incoming Admin authentication request. A non-admin account is
     * rejected outright — this entry point never hands out Customer/Seller access.
     */
    public function storeAdmin(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        if (! $request->user()->isAdmin()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            throw ValidationException::withMessages([
                'email' => __('Ces identifiants ne correspondent pas à un compte administrateur.'),
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
