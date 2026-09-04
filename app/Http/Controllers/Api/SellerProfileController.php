<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SellerProfile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SellerProfileController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $profile = $request->user()->sellerProfile;

        return response()->json([
            'seller_profile' => $profile ? [
                'shop_name' => $profile->shop_name,
                'status' => $profile->status,
                'submitted_at' => $profile->submitted_at,
                'reviewed_at' => $profile->reviewed_at,
            ] : null,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $user = $request->user();

        if ($user->sellerProfile) {
            return response()->json(['message' => 'Une demande existe déjà.'], 422);
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

        return response()->json(['message' => 'Demande envoyée.', 'status' => $profile->status], 201);
    }
}
