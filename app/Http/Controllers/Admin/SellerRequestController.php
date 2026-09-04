<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SellerProfile;
use App\Models\Shop;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SellerRequestController extends Controller
{
    /**
     * List seller subscription requests awaiting review, plus recent decisions.
     */
    public function index(): View
    {
        return view('admin.seller-requests.index', [
            'pending' => SellerProfile::with('user')->where('status', 'pending')->latest('submitted_at')->get(),
            'reviewed' => SellerProfile::with(['user', 'reviewer'])->whereIn('status', ['active', 'rejected', 'suspended'])->latest('reviewed_at')->take(20)->get(),
        ]);
    }

    /**
     * Approve a seller subscription request: activate it and provision its shop.
     */
    public function approve(Request $request, SellerProfile $sellerProfile): RedirectResponse
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

        return back()->with('status', 'seller-request-approved');
    }

    /**
     * Reject a seller subscription request.
     */
    public function reject(Request $request, SellerProfile $sellerProfile): RedirectResponse
    {
        $validated = $request->validate([
            'rejection_reason' => ['nullable', 'string', 'max:255'],
        ]);

        $sellerProfile->status = 'rejected';
        $sellerProfile->reviewed_at = now();
        $sellerProfile->reviewed_by = $request->user()->id;
        $sellerProfile->rejection_reason = $validated['rejection_reason'] ?? null;
        $sellerProfile->save();

        return back()->with('status', 'seller-request-rejected');
    }
}
