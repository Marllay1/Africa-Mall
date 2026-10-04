<?php

namespace App\Http\Controllers;

use App\Actions\InsufficientStockException;
use App\Actions\PlaceOrder;
use App\Models\Coupon;
use App\Models\PaymentMethod;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
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

        return view('cart.payment', $cart + [
            'paymentMethods' => PaymentMethod::active()->get(),
            'addresses' => $request->user()->addresses()->orderByDesc('is_default')->latest()->get(),
        ]);
    }

    private function cartLines(Request $request): array
    {
        $cart = $this->cart($request);
        $products = Product::whereIn('id', array_keys($cart))->with('shop')->get()->keyBy('id');

        $lines = [];
        $subtotal = 0;

        foreach ($cart as $productId => $quantity) {
            $product = $products->get($productId);

            if (! $product) {
                continue;
            }

            $lineTotal = $product->effectivePrice() * $quantity;
            $subtotal += $lineTotal;

            $lines[] = [
                'product' => $product,
                'quantity' => $quantity,
                'line_total' => $lineTotal,
            ];
        }

        $coupon = $this->activeCoupon($request, $subtotal);
        $discount = $coupon ? $coupon->discountFor($subtotal) : 0;

        return [
            'lines' => $lines,
            'subtotal' => $subtotal,
            'total' => $subtotal - $discount,
            'coupon' => $coupon,
            'discount' => $discount,
        ];
    }

    /**
     * The coupon stored in session, dropped silently if it is no longer valid
     * (deactivated/expired by the time the cart is revisited).
     */
    private function activeCoupon(Request $request, int $subtotal): ?Coupon
    {
        $code = $request->session()->get('coupon_code');

        if (! $code) {
            return null;
        }

        $coupon = Coupon::where('code', $code)->first();

        if (! $coupon || ! $coupon->isValidFor($subtotal)) {
            $request->session()->forget('coupon_code');

            return null;
        }

        return $coupon;
    }

    public function applyCoupon(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'coupon_code' => ['required', 'string', 'max:50'],
        ]);

        $subtotal = $this->cartLines($request)['subtotal'];
        $coupon = Coupon::where('code', strtoupper($validated['coupon_code']))->first();

        if (! $coupon || ! $coupon->isValidFor($subtotal)) {
            return back()->with('status', 'coupon-invalid');
        }

        $request->session()->put('coupon_code', $coupon->code);

        return back()->with('status', 'coupon-applied');
    }

    public function removeCoupon(Request $request): RedirectResponse
    {
        $request->session()->forget('coupon_code');

        return back()->with('status', 'coupon-removed');
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
            'payment_method' => ['required', 'in:'.implode(',', PaymentMethod::activeCodes())],
            'address_id' => ['required', 'integer'],
        ]);

        $address = $request->user()->addresses()->findOrFail($validated['address_id']);

        $subtotal = $this->cartLines($request)['subtotal'];
        $coupon = $this->activeCoupon($request, $subtotal);

        try {
            $placeOrder->execute($request->user(), $cart, $validated['payment_method'], $coupon, $address->formatted());
        } catch (InsufficientStockException) {
            return redirect()->route('cart.show')->with('status', 'stock-insufficient');
        }

        $request->session()->forget(['cart', 'coupon_code']);

        return redirect()->route('orders.index')->with('status', 'order-placed');
    }

    private function cart(Request $request): array
    {
        return $request->session()->get('cart', []);
    }
}
