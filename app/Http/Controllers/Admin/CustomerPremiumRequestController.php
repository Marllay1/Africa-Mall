<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerPremiumSubscription;
use App\Notifications\CustomerPremiumSubscriptionReviewed;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class CustomerPremiumRequestController extends Controller
{
    public function index(): View
    {
        return view('admin.customer-premium-requests.index', [
            'pending' => CustomerPremiumSubscription::with('user')->where('status', 'pending')->latest('requested_at')->get(),
            'reviewed' => CustomerPremiumSubscription::with(['user', 'reviewer'])->whereIn('status', ['active', 'rejected'])->latest('reviewed_at')->take(20)->get(),
        ]);
    }

    public function approve(Request $request, CustomerPremiumSubscription $customerPremiumSubscription): RedirectResponse
    {
        $customerPremiumSubscription->status = 'active';
        $customerPremiumSubscription->reviewed_at = now();
        $customerPremiumSubscription->reviewed_by = $request->user()->id;
        $customerPremiumSubscription->expires_at = now()->addMonth();
        $customerPremiumSubscription->save();

        try {
            $customerPremiumSubscription->user->notify(new CustomerPremiumSubscriptionReviewed($customerPremiumSubscription));
        } catch (Throwable $e) {
            report($e);
        }

        return back()->with('status', 'premium-request-approved');
    }

    public function reject(Request $request, CustomerPremiumSubscription $customerPremiumSubscription): RedirectResponse
    {
        $validated = $request->validate([
            'rejection_reason' => ['nullable', 'string', 'max:255'],
        ]);

        $customerPremiumSubscription->status = 'rejected';
        $customerPremiumSubscription->reviewed_at = now();
        $customerPremiumSubscription->reviewed_by = $request->user()->id;
        $customerPremiumSubscription->rejection_reason = $validated['rejection_reason'] ?? null;
        $customerPremiumSubscription->save();

        try {
            $customerPremiumSubscription->user->notify(new CustomerPremiumSubscriptionReviewed($customerPremiumSubscription));
        } catch (Throwable $e) {
            report($e);
        }

        return back()->with('status', 'premium-request-rejected');
    }
}
