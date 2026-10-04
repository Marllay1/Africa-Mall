<?php

namespace App\Actions;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Notifications\NewOrderReceived;
use Illuminate\Support\Facades\DB;
use Throwable;

class PlaceGuestOrder
{
    /**
     * Create a single-product order with no account, for a non-connected visitor (§29).
     */
    public function execute(Product $product, int $quantity, string $paymentMethod, string $guestName, string $guestPhone, string $guestAddress): Order
    {
        if ($quantity > $product->stock) {
            throw new InsufficientStockException($product->id);
        }

        $order = DB::transaction(function () use ($product, $quantity, $paymentMethod, $guestName, $guestPhone, $guestAddress): Order {
            $total = $product->effectivePrice() * $quantity;

            $order = new Order([
                'status' => 'pending',
                'total' => $total,
                'devise' => 'XOF',
                'guest_name' => $guestName,
                'guest_phone' => $guestPhone,
                'guest_address' => $guestAddress,
            ]);
            $order->shop_id = $product->shop_id;
            $order->save();

            $item = new OrderItem(['quantity' => $quantity, 'unit_price' => $product->effectivePrice()]);
            $item->order_id = $order->id;
            $item->product_id = $product->id;
            $item->save();

            $product->decrement('stock', $quantity);

            $payment = new Payment(['method' => $paymentMethod, 'status' => 'pending', 'amount' => $total, 'devise' => 'XOF']);
            $payment->order_id = $order->id;
            $payment->save();

            return $order;
        });

        try {
            $product->shop->sellerProfile->user->notify(new NewOrderReceived($order));
        } catch (Throwable $e) {
            report($e);
        }

        return $order;
    }
}
