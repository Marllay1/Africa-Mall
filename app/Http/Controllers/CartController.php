<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CartController extends Controller
{
    private const PAYMENT_METHODS = ['mobile_money', 'carte', 'livraison'];

    public function show(Request $request): View
    {
        return view('cart.show', $this->cartLines($request));
    }

    public function showPayment(Request $request): View|RedirectResponse
    {
        $cart = $this->cartLines($request);

        if (empty($cart['lines'])) {
            return redirect()->route('cart.show')->with('status', 'cart-empty');
        }

        return view('cart.payment', $cart);
    }

    private function cartLines(Request $request): array
    {
        $cart = $this->cart($request);
        $products = Product::whereIn('id', array_keys($cart))->with('shop')->get()->keyBy('id');

        $lines = [];
        $total = 0;

        foreach ($cart as $productId => $quantity) {
            $product = $products->get($productId);

            if (! $product) {
                continue;
            }

            $lineTotal = $product->effectivePrice() * $quantity;
            $total += $lineTotal;

            $lines[] = [
                'product' => $product,
                'quantity' => $quantity,
                'line_total' => $lineTotal,
            ];
        }

        return ['lines' => $lines, 'total' => $total];
    }

    public function add(Request $request, Product $product): RedirectResponse
    {
        abort_unless($product->is_active, 404);

        if ($product->stock < 1) {
            return back()->with('status', 'out-of-stock');
        }

        $quantity = max(1, $request->integer('quantity', 1));
        $cart = $this->cart($request);
        $cart[$product->id] = min($product->stock, ($cart[$product->id] ?? 0) + $quantity);
        $request->session()->put('cart', $cart);

        return back()->with('status', 'added-to-cart');
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $quantity = $request->integer('quantity', 1);
        $cart = $this->cart($request);

        if ($quantity < 1) {
            unset($cart[$product->id]);
        } else {
            $cart[$product->id] = min($product->stock, $quantity);
        }

        $request->session()->put('cart', $cart);

        return redirect()->route('cart.show');
    }

    public function remove(Request $request, Product $product): RedirectResponse
    {
        $cart = $this->cart($request);
        unset($cart[$product->id]);
        $request->session()->put('cart', $cart);

        return redirect()->route('cart.show');
    }

    public function buyNow(Request $request, Product $product): RedirectResponse
    {
        abort_unless($product->is_active, 404);

        if ($product->stock < 1) {
            return back()->with('status', 'out-of-stock');
        }

        $quantity = max(1, $request->integer('quantity', 1));
        $cart = $this->cart($request);
        $cart[$product->id] = min($product->stock, ($cart[$product->id] ?? 0) + $quantity);
        $request->session()->put('cart', $cart);

        return redirect()->route('cart.payment');
    }

    public function checkout(Request $request): RedirectResponse
    {
        $cart = $this->cart($request);

        if (empty($cart)) {
            return redirect()->route('cart.show')->with('status', 'cart-empty');
        }

        $validated = $request->validate([
            'payment_method' => ['required', 'in:'.implode(',', self::PAYMENT_METHODS)],
        ]);

        $products = Product::whereIn('id', array_keys($cart))->get()->keyBy('id');

        foreach ($cart as $productId => $quantity) {
            $product = $products->get($productId);

            if (! $product || $quantity > $product->stock) {
                return redirect()->route('cart.show')->with('status', 'stock-insufficient');
            }
        }

        DB::transaction(function () use ($cart, $products, $request, $validated): void {
            $byShop = $products->groupBy('shop_id');

            foreach ($byShop as $shopId => $shopProducts) {
                $total = 0;

                foreach ($shopProducts as $product) {
                    $total += $product->effectivePrice() * $cart[$product->id];
                }

                $order = new Order(['status' => 'pending', 'total' => $total, 'devise' => 'XOF']);
                $order->user_id = $request->user()->id;
                $order->shop_id = $shopId;
                $order->save();

                foreach ($shopProducts as $product) {
                    $quantity = $cart[$product->id];

                    $item = new OrderItem(['quantity' => $quantity, 'unit_price' => $product->effectivePrice()]);
                    $item->order_id = $order->id;
                    $item->product_id = $product->id;
                    $item->save();

                    $product->decrement('stock', $quantity);
                }

                $payment = new Payment(['method' => $validated['payment_method'], 'status' => 'pending', 'amount' => $total, 'devise' => 'XOF']);
                $payment->order_id = $order->id;
                $payment->save();
            }
        });

        $request->session()->forget('cart');

        return redirect()->route('orders.index')->with('status', 'order-placed');
    }

    private function cart(Request $request): array
    {
        return $request->session()->get('cart', []);
    }
}
