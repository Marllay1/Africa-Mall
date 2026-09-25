<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\PremiumSubscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PremiumController extends Controller
{
    public function show(Request $request): View
    {
        $shop = $request->user()->sellerProfile->shop;

        return view('seller.premium.show', [
            'tiers' => PremiumSubscription::TIERS,
            'isPremium' => $shop->isPremium(),
            'currentTierLabel' => $shop->premiumTierLabel(),
            'latestSubscription' => $shop->premiumSubscriptions()->latest()->first(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $shop = $request->user()->sellerProfile->shop;

        $validated = $request->validate([
            'tier' => ['required', 'in:'.implode(',', array_keys(PremiumSubscription::TIERS))],
        ]);

        if ($shop->premiumSubscriptions()->where('status', 'pending')->exists()) {
            return back();
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

        return back()->with('status', 'premium-request-submitted');
    }
}
