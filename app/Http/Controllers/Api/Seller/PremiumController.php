<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Controller;
use App\Models\PremiumSubscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PremiumController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $shop = $request->user()->sellerProfile->shop;

        return response()->json([
            'tiers' => PremiumSubscription::TIERS,
            'is_premium' => $shop->isPremium(),
            'current_tier_label' => $shop->premiumTierLabel(),
            'latest_subscription' => $shop->premiumSubscriptions()->latest()->first(),
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $shop = $request->user()->sellerProfile->shop;

        $validated = $request->validate([
            'tier' => ['required', 'in:'.implode(',', array_keys(PremiumSubscription::TIERS))],
        ]);

        if ($shop->premiumSubscriptions()->where('status', 'pending')->exists()) {
            return response()->json(['message' => 'Une demande est déjà en attente.'], 422);
        }

        $subscription = new PremiumSubscription([
            'tier' => $validated['tier'],
            'price' => PremiumSubscription::TIERS[$validated['tier']]['price'],
            'devise' => $shop->sellerProfile->devise,
            'status' => 'pending',
        ]);
        $subscription->shop_id = $shop->id;
        $subscription->requested_at = now();
        $subscription->save();

        return response()->json(['message' => 'Demande envoyée.', 'status' => $subscription->status], 201);
    }
}
