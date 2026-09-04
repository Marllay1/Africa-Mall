<?php

namespace App\Http\Controllers;

use App\Actions\InsufficientStockException;
use App\Actions\PlaceOrder;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    private const PAYMENT_METHODS = PlaceOrder::PAYMENT_METHODS;

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

    public function checkout(Request $request, PlaceOrder $placeOrder): RedirectResponse
    {
        $cart = $this->cart($request);

        if (empty($cart)) {
            return redirect()->route('cart.show')->with('status', 'cart-empty');
        }

        $validated = $request->validate([
            'payment_method' => ['required', 'in:'.implode(',', self::PAYMENT_METHODS)],
        ]);

        try {
            $placeOrder->execute($request->user(), $cart, $validated['payment_method']);
        } catch (InsufficientStockException) {
            return redirect()->route('cart.show')->with('status', 'stock-insufficient');
        }

        $request->session()->forget('cart');

        return redirect()->route('orders.index')->with('status', 'order-placed');
    }

    private function cart(Request $request): array
    {
        return $request->session()->get('cart', []);
    }
}
