<?php

namespace App\Http\Controllers;

use App\Actions\InsufficientStockException;
use App\Actions\PlaceGuestOrder;
use App\Models\Order;
use App\Models\PaymentMethod;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GuestOrderController extends Controller
{
    public function store(Request $request, Product $product, PlaceGuestOrder $placeGuestOrder): RedirectResponse
    {
        abort_unless($product->is_active, 404);
        abort_if(auth()->check(), 403);

        $validated = $request->validate([
            'guest_name' => ['required', 'string', 'max:255'],
            'guest_phone' => ['required', 'string', 'max:30'],
            'guest_address' => ['required', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:1'],
            'payment_method' => ['required', 'in:'.implode(',', PaymentMethod::activeCodes())],
        ]);

        if ($product->stock < 1) {
            return back()->with('status', 'out-of-stock');
        }

        try {
            $order = $placeGuestOrder->execute(
                $product,
                min($validated['quantity'], $product->stock),
                $validated['payment_method'],
                $validated['guest_name'],
                $validated['guest_phone'],
                $validated['guest_address'],
            );
        } catch (InsufficientStockException) {
            return back()->with('status', 'stock-insufficient');
        }

        return redirect()->route('guest-orders.confirmation', $order);
    }

    public function confirmation(Order $order): View
    {
        abort_if($order->user_id !== null, 404);

        return view('orders.guest-confirmation', ['order' => $order->load('items.product', 'shop')]);
    }
}
