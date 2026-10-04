<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CouponController extends Controller
{
    public function index(): View
    {
        return view('admin.coupons.index', [
            'coupons' => Coupon::latest()->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:50', 'unique:coupons,code'],
            'type' => ['required', 'in:percent,fixed'],
            'value' => ['required', 'integer', 'min:1'],
            'min_order_total' => ['nullable', 'integer', 'min:0'],
            'usage_limit' => ['nullable', 'integer', 'min:1'],
            'starts_at' => ['nullable', 'date'],
            'expires_at' => ['nullable', 'date', 'after:starts_at'],
        ]);

        if ($validated['type'] === 'percent' && $validated['value'] > 100) {
            return back()->withErrors(['value' => __('Un pourcentage ne peut pas dépasser 100.')])->withInput();
        }

        $validated['code'] = Str::upper($validated['code']);
        $coupon = new Coupon($validated + ['is_active' => true]);
        $coupon->save();

        return back()->with('status', 'coupon-created');
    }

    public function toggle(Coupon $coupon): RedirectResponse
    {
        $coupon->is_active = ! $coupon->is_active;
        $coupon->save();

        return back()->with('status', $coupon->is_active ? 'coupon-enabled' : 'coupon-disabled');
    }

    public function destroy(Coupon $coupon): RedirectResponse
    {
        if ($coupon->used_count > 0) {
            return back()->with('status', 'coupon-in-use');
        }

        $coupon->delete();

        return back()->with('status', 'coupon-deleted');
    }
}
