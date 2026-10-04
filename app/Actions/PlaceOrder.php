<?php

namespace App\Actions;

use App\Models\Coupon;
use App\Models\CustomerPremiumSubscription;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use App\Notifications\NewOrderReceived;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Throwable;

class PlaceOrder
{
    /**
     * Create one Order per shop for the given items, decrementing stock and recording a Payment.
     * A coupon's discount is split across shops in proportion to each shop's share of the cart.
     *
     * @param  array<int, int>  $items  product_id => quantity
     * @return Collection<int, Order>
     *
     * @throws InsufficientStockException
     */
    public function execute(User $user, array $items, string $paymentMethod, ?Coupon $coupon = null, ?string $deliveryAddress = null): Collection
    {
        $products = Product::whereIn('id', array_keys($items))->get()->keyBy('id');

        foreach ($items as $productId => $quantity) {
            $product = $products->get($productId);

            if (! $product || $quantity > $product->stock) {
                throw new InsufficientStockException((int) $productId);
            }
        }

        $shops = Shop::with('sellerProfile.user')->whereIn('id', $products->pluck('shop_id')->unique())->get()->keyBy('id');

        $subtotal = 0;
        foreach ($products as $product) {
            $subtotal += $product->effectivePrice() * $items[$product->id];
        }
        $totalDiscount = $coupon ? $coupon->discountFor($subtotal) : 0;

        if ($user->isPremiumCustomer()) {
            $totalDiscount += (int) round(($subtotal - $totalDiscount) * CustomerPremiumSubscription::DISCOUNT_PERCENT / 100);
        }

        $orders = DB::transaction(function () use ($items, $products, $user, $paymentMethod, $coupon, $subtotal, $totalDiscount, $deliveryAddress): Collection {
            $orders = new Collection;

            foreach ($products->groupBy('shop_id') as $shopId => $shopProducts) {
                $shopSubtotal = 0;

                foreach ($shopProducts as $product) {
                    $shopSubtotal += $product->effectivePrice() * $items[$product->id];
                }

                $shopDiscount = $totalDiscount > 0 ? (int) round($totalDiscount * $shopSubtotal / $subtotal) : 0;
                $total = $shopSubtotal - $shopDiscount;

                $order = new Order([
                    'status' => 'pending',
                    'total' => $total,
                    'devise' => 'XOF',
                    'coupon_code' => $coupon?->code,
                    'discount_amount' => $shopDiscount,
                    'delivery_address' => $deliveryAddress,
                ]);
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

            $coupon?->increment('used_count');

            return $orders;
        });

        foreach ($orders as $order) {
            try {
                $shops[$order->shop_id]->sellerProfile->user->notify(new NewOrderReceived($order));
            } catch (Throwable $e) {
                report($e);
            }
        }

        return $orders;
    }
}
