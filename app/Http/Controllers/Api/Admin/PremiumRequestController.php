<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\PremiumSubscriptionResource;
use App\Models\PremiumSubscription;
use App\Notifications\PremiumSubscriptionReviewed;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class PremiumRequestController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'pending' => PremiumSubscriptionResource::collection(
                PremiumSubscription::with('shop')->where('status', 'pending')->latest('requested_at')->get()
            ),
            'reviewed' => PremiumSubscriptionResource::collection(
                PremiumSubscription::with(['shop', 'reviewer'])->whereIn('status', ['active', 'rejected'])->latest('reviewed_at')->take(20)->get()
            ),
        ]);
    }

    public function approve(Request $request, PremiumSubscription $premiumSubscription): PremiumSubscriptionResource
    {
        $premiumSubscription->status = 'active';
        $premiumSubscription->reviewed_at = now();
        $premiumSubscription->reviewed_by = $request->user()->id;
        $premiumSubscription->expires_at = now()->addMonth();
        $premiumSubscription->save();

        try {
            $premiumSubscription->shop->sellerProfile->user->notify(new PremiumSubscriptionReviewed($premiumSubscription));
        } catch (Throwable $e) {
            report($e);
        }

        return new PremiumSubscriptionResource($premiumSubscription->load('shop'));
    }

    public function reject(Request $request, PremiumSubscription $premiumSubscription): PremiumSubscriptionResource
    {
        $validated = $request->validate([
            'rejection_reason' => ['nullable', 'string', 'max:255'],
        ]);

        $premiumSubscription->status = 'rejected';
        $premiumSubscription->reviewed_at = now();
        $premiumSubscription->reviewed_by = $request->user()->id;
        $premiumSubscription->rejection_reason = $validated['rejection_reason'] ?? null;
        $premiumSubscription->save();

        try {
            $premiumSubscription->shop->sellerProfile->user->notify(new PremiumSubscriptionReviewed($premiumSubscription));
        } catch (Throwable $e) {
            report($e);
        }

        return new PremiumSubscriptionResource($premiumSubscription->load('shop'));
    }
}
