<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    private const STATUSES = ['pending', 'confirmed', 'shipped', 'delivered', 'cancelled'];

    public function index(Request $request): View
    {
        $shop = $request->user()->sellerProfile->shop;

        return view('seller.orders.index', [
            'orders' => $shop->orders()->with('user', 'items.product')->latest()->paginate(15),
            'statuses' => self::STATUSES,
        ]);
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        abort_unless($order->shop_id === $request->user()->sellerProfile->shop->id, 403);

        $validated = $request->validate([
            'status' => ['required', 'in:'.implode(',', self::STATUSES)],
        ]);

        $order->status = $validated['status'];
        $order->save();

        return back()->with('status', 'order-status-updated');
    }
}
