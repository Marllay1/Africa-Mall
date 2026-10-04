<?php

namespace App\Http\Controllers;

use App\Models\CustomerPremiumSubscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PremiumController extends Controller
{
    public function show(Request $request): View
    {
        $user = $request->user();

        return view('premium.show', [
            'isPremium' => $user->isPremiumCustomer(),
            'latestSubscription' => $user->customerPremiumSubscriptions()->latest()->first(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->customerPremiumSubscriptions()->where('status', 'pending')->exists()) {
            return back();
        }

        $subscription = new CustomerPremiumSubscription([
            'price' => CustomerPremiumSubscription::PRICE,
            'devise' => 'XOF',
            'status' => 'pending',
        ]);
        $subscription->user_id = $user->id;
        $subscription->requested_at = now();
        $subscription->save();

        return back()->with('status', 'premium-request-submitted');
    }
}
