<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    private const CANCELLABLE_STATUSES = ['pending', 'confirmed'];

    private const RETURN_WINDOW_DAYS = 14;

    public function index(Request $request): View
    {
        return view('orders.index', [
            'orders' => $request->user()->orders()->with('shop')->latest()->paginate(10),
        ]);
    }

    public function show(Request $request, Order $order): View
    {
        abort_unless($order->user_id === $request->user()->id, 403);

        return view('orders.show', [
            'order' => $order->load('shop', 'items.product'),
        ]);
    }

    public function cancel(Request $request, Order $order): RedirectResponse
    {
        abort_unless($order->user_id === $request->user()->id, 403);
        abort_unless(in_array($order->status, self::CANCELLABLE_STATUSES, true), 404);

        $order->load('items');

        foreach ($order->items as $item) {
            $item->product?->increment('stock', $item->quantity);
        }

        $order->status = 'cancelled';
        $order->save();

        return back()->with('status', 'order-cancelled');
    }

    public function requestReturn(Request $request, Order $order): RedirectResponse
    {
        abort_unless($order->user_id === $request->user()->id, 403);
        abort_unless($order->status === 'delivered', 404);
        abort_unless($order->updated_at->diffInDays(now()) <= self::RETURN_WINDOW_DAYS, 404);

        $validated = $request->validate([
            'reason' => ['required', 'string', 'max:1000'],
        ]);

        $order->status = 'litige';
        $order->save();

        $dispute = $order->dispute;

        if ($dispute) {
            $dispute->admin_notes = __('Motif du client :').' '.$validated['reason'];
            $dispute->save();
        }

        return back()->with('status', 'return-requested');
    }
}
