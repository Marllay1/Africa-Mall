<?php

namespace App\Http\Controllers\Api;

use App\Actions\InsufficientStockException;
use App\Actions\PlaceOrder;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OrderController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $orders = $request->user()->orders()->with('shop', 'items.product', 'payment')->latest()->paginate(10);

        return OrderResource::collection($orders);
    }

    public function show(Request $request, Order $order): OrderResource
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        return new OrderResource($order->load('shop', 'items.product', 'payment'));
    }

    public function store(Request $request, PlaceOrder $placeOrder): JsonResponse
    {
        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'payment_method' => ['required', 'in:'.implode(',', PlaceOrder::PAYMENT_METHODS)],
        ]);

        $items = collect($validated['items'])->pluck('quantity', 'product_id')->all();

        try {
            $orders = $placeOrder->execute($request->user(), $items, $validated['payment_method']);
        } catch (InsufficientStockException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        }

        return OrderResource::collection($orders->load('shop', 'items.product', 'payment'))
            ->response()
            ->setStatusCode(201);
    }
}
