<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OrderController extends Controller
{
    private const STATUSES = ['pending', 'confirmed', 'preparation', 'shipped', 'delivered', 'cancelled', 'litige'];

    public function index(Request $request): AnonymousResourceCollection
    {
        $shop = $request->user()->sellerProfile->shop;

        return OrderResource::collection($shop->orders()->with('user', 'items.product', 'payment')->latest()->paginate(15));
    }

    public function updateStatus(Request $request, Order $order): OrderResource
    {
        abort_unless($order->shop_id === $request->user()->sellerProfile->shop->id, 403);

        $validated = $request->validate([
            'status' => ['required', 'in:'.implode(',', self::STATUSES)],
        ]);

        $order->status = $validated['status'];
        $order->save();

        return new OrderResource($order->load('shop', 'items.product', 'payment'));
    }
}
