<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingsController extends Controller
{
    public function edit(Request $request): View
    {
        $sellerProfile = $request->user()->sellerProfile;

        return view('seller.settings.edit', [
            'shop' => $sellerProfile->shop,
            'sellerProfile' => $sellerProfile,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $sellerProfile = $request->user()->sellerProfile;
        $shop = $sellerProfile->shop;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'logo_path' => ['nullable', 'url', 'max:2048'],
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

        $shop->name = $validated['name'];
        $shop->description = $validated['description'] ?? null;
        $shop->logo_path = $validated['logo_path'] ?? null;
        $shop->save();

        $sellerProfile->fill([
            'regime' => $validated['regime'] ?? null,
            'adresse' => $validated['adresse'],
            'payment_mode' => $validated['payment_mode'],
            'numero_om' => $validated['numero_om'] ?? null,
            'email_paypal' => $validated['email_paypal'] ?? null,
            'nom_banque' => $validated['nom_banque'] ?? null,
            'numero_compte' => $validated['numero_compte'] ?? null,
            'titulaire_compte' => $validated['titulaire_compte'] ?? null,
            'devise' => $validated['devise'],
        ]);
        $sellerProfile->save();

        return back()->with('status', 'settings-updated');
    }
}
