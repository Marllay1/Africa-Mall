<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Notifications\OrderStatusUpdated;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Throwable;

class OrderController extends Controller
{
    private const STATUSES = ['pending', 'confirmed', 'preparation', 'shipped', 'delivered', 'cancelled', 'litige', 'remboursee'];

    /**
     * Seller-allowed forward transitions. 'litige' and 'remboursee' are never
     * reachable from here — 'litige' is triggered automatically by a customer
     * return request, and only Admin dispute resolution can set 'remboursee'
     * or release a 'litige' order back to 'delivered'.
     */
    private const TRANSITIONS = [
        'pending' => ['confirmed', 'cancelled'],
        'confirmed' => ['preparation', 'cancelled'],
        'preparation' => ['shipped'],
        'shipped' => ['delivered'],
        'delivered' => [],
        'cancelled' => [],
        'litige' => [],
        'remboursee' => [],
    ];

    public function index(Request $request): View
    {
        $shop = $request->user()->sellerProfile->shop;

        $query = $shop->orders()->with('user', 'items.product')->latest();

        if ($search = trim((string) $request->query('search'))) {
            $numericId = (int) preg_replace('/\D/', '', $search);

            $query->where(function ($q) use ($search, $numericId) {
                $q->where('guest_name', 'like', "%{$search}%")
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', "%{$search}%"));

                if ($numericId > 0) {
                    $q->orWhere('id', $numericId);
                }
            });
        }

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        return view('seller.orders.index', [
            'orders' => $query->paginate(15)->withQueryString(),
            'statuses' => self::STATUSES,
            'transitions' => self::TRANSITIONS,
        ]);
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        abort_unless($order->shop_id === $request->user()->sellerProfile->shop->id, 403);

        $allowed = self::TRANSITIONS[$order->status] ?? [];

        abort_if($allowed === [], 403, __("Cette commande ne peut plus changer de statut depuis l'espace vendeur."));

        $validated = $request->validate([
            'status' => ['required', 'in:'.implode(',', $allowed)],
        ]);

        $order->status = $validated['status'];
        $order->save();

        if ($order->user) {
            try {
                $order->user->notify(new OrderStatusUpdated($order));
            } catch (Throwable $e) {
                report($e);
            }
        }

        return back()->with('status', 'order-status-updated');
    }
}
