<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\SellerProfileResource;
use App\Models\SellerProfile;
use App\Models\Shop;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SellerRequestController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'pending' => SellerProfileResource::collection(
                SellerProfile::with('user')->where('status', 'pending')->latest('submitted_at')->get()
            ),
            'reviewed' => SellerProfileResource::collection(
                SellerProfile::with(['user', 'reviewer'])->whereIn('status', ['active', 'rejected', 'suspended'])->latest('reviewed_at')->take(20)->get()
            ),
        ]);
    }

    public function approve(Request $request, SellerProfile $sellerProfile): SellerProfileResource
    {
        $sellerProfile->status = 'active';
        $sellerProfile->reviewed_at = now();
        $sellerProfile->reviewed_by = $request->user()->id;
        $sellerProfile->save();

        if (! $sellerProfile->shop) {
            $slug = Str::slug($sellerProfile->shop_name).'-'.$sellerProfile->id;

            $shop = new Shop([
                'name' => $sellerProfile->shop_name,
                'slug' => $slug,
            ]);
            $shop->seller_profile_id = $sellerProfile->id;
            $shop->save();
        }

        return new SellerProfileResource($sellerProfile->load('user'));
    }

    public function reject(Request $request, SellerProfile $sellerProfile): SellerProfileResource
    {
        $validated = $request->validate([
            'rejection_reason' => ['nullable', 'string', 'max:255'],
        ]);

        $sellerProfile->status = 'rejected';
        $sellerProfile->reviewed_at = now();
        $sellerProfile->reviewed_by = $request->user()->id;
        $sellerProfile->rejection_reason = $validated['rejection_reason'] ?? null;
        $sellerProfile->save();

        return new SellerProfileResource($sellerProfile->load('user'));
    }
}
