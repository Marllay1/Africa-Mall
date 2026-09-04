<?php

namespace App\Http\Controllers;

use App\Models\SellerProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SellerSubscriptionController extends Controller
{
    /**
     * Show the seller subscription form, or the current request's status.
     */
    public function show(Request $request): View
    {
        return view('seller-subscription.show', [
            'sellerProfile' => $request->user()->sellerProfile,
        ]);
    }

    /**
     * Submit a seller subscription request for the authenticated user.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->sellerProfile) {
            return redirect()->route('seller-subscription.show');
        }

        $validated = $request->validate([
            'shop_name' => ['required', 'string', 'max:255'],
            'regime' => ['nullable', 'string', 'max:255'],
            'adresse' => ['required', 'string', 'max:255'],
            'payment_mode' => ['required', 'in:orange,paypal,banque'],
            'numero_om' => ['required_if:payment_mode,orange', 'nullable', 'string', 'max:30'],
            'email_paypal' => ['required_if:payment_mode,paypal', 'nullable', 'email', 'max:255'],
            'nom_banque' => ['required_if:payment_mode,banque', 'nullable', 'string', 'max:255'],
            'numero_compte' => ['required_if:payment_mode,banque', 'nullable', 'string', 'max:255'],
            'titulaire_compte' => ['required_if:payment_mode,banque', 'nullable', 'string', 'max:255'],
            'devise' => ['required', 'string', 'max:10'],
        ]);

        $profile = new SellerProfile($validated);
        $profile->user_id = $user->id;
        $profile->status = 'pending';
        $profile->submitted_at = now();
        $profile->save();

        return redirect()->route('seller-subscription.show')
            ->with('status', 'seller-request-submitted');
    }
}
