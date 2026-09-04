<?php

namespace App\Actions;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class PlaceOrder
{
    public const PAYMENT_METHODS = ['mobile_money', 'carte', 'livraison'];

    /**
     * Create one Order per shop for the given items, decrementing stock and recording a Payment.
     *
     * @param  array<int, int>  $items  product_id => quantity
     * @return Collection<int, Order>
     *
     * @throws InsufficientStockException
     */
    public function execute(User $user, array $items, string $paymentMethod): Collection
    {
        $products = Product::whereIn('id', array_keys($items))->get()->keyBy('id');

        foreach ($items as $productId => $quantity) {
            $product = $products->get($productId);

            if (! $product || $quantity > $product->stock) {
                throw new InsufficientStockException((int) $productId);
            }
        }

        return DB::transaction(function () use ($items, $products, $user, $paymentMethod): Collection {
            $orders = new Collection();

            foreach ($products->groupBy('shop_id') as $shopId => $shopProducts) {
                $total = 0;

                foreach ($shopProducts as $product) {
                    $total += $product->effectivePrice() * $items[$product->id];
                }

                $order = new Order(['status' => 'pending', 'total' => $total, 'devise' => 'XOF']);
                $order->user_id = $user->id;
                $order->shop_id = $shopId;
                $order->save();

                foreach ($shopProducts as $product) {
                    $quantity = $items[$product->id];

                    $item = new OrderItem(['quantity' => $quantity, 'unit_price' => $product->effectivePrice()]);
                    $item->order_id = $order->id;
                    $item->product_id = $product->id;
                    $item->save();

                    $product->decrement('stock', $quantity);
                }

                $payment = new Payment(['method' => $paymentMethod, 'status' => 'pending', 'amount' => $total, 'devise' => 'XOF']);
                $payment->order_id = $order->id;
                $payment->save();

                $orders->push($order);
            }

            return $orders;
        });
    }
}
