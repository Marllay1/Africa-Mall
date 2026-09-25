<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PremiumSubscription;
use App\Notifications\PremiumSubscriptionReviewed;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PremiumRequestController extends Controller
{
    /**
     * List Premium subscription requests awaiting review, plus recent decisions.
     */
    public function index(): View
    {
        return view('admin.premium-requests.index', [
            'pending' => PremiumSubscription::with('shop')->where('status', 'pending')->latest('requested_at')->get(),
            'reviewed' => PremiumSubscription::with(['shop', 'reviewer'])->whereIn('status', ['active', 'rejected'])->latest('reviewed_at')->take(20)->get(),
        ]);
    }

    /**
     * Approve a Premium subscription request: activate it for one month.
     */
    public function approve(Request $request, PremiumSubscription $premiumSubscription): RedirectResponse
    {
        $premiumSubscription->status = 'active';
        $premiumSubscription->reviewed_at = now();
        $premiumSubscription->reviewed_by = $request->user()->id;
        $premiumSubscription->expires_at = now()->addMonth();
        $premiumSubscription->save();

        $premiumSubscription->shop->sellerProfile->user->notify(new PremiumSubscriptionReviewed($premiumSubscription));

        return back()->with('status', 'premium-request-approved');
    }

    /**
     * Reject a Premium subscription request.
     */
    public function reject(Request $request, PremiumSubscription $premiumSubscription): RedirectResponse
    {
        $validated = $request->validate([
            'rejection_reason' => ['nullable', 'string', 'max:255'],
        ]);

        $premiumSubscription->status = 'rejected';
        $premiumSubscription->reviewed_at = now();
        $premiumSubscription->reviewed_by = $request->user()->id;
        $premiumSubscription->rejection_reason = $validated['rejection_reason'] ?? null;
        $premiumSubscription->save();

        $premiumSubscription->shop->sellerProfile->user->notify(new PremiumSubscriptionReviewed($premiumSubscription));

        return back()->with('status', 'premium-request-rejected');
    }
}
