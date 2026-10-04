<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    private const STATUSES = ['pending', 'confirmed', 'preparation', 'shipped', 'delivered', 'cancelled', 'litige'];

    public function index(Request $request): View
    {
        $orders = Order::with('user', 'shop', 'items.product')
            ->when($request->string('status')->toString(), fn ($query, $status) => $query->where('status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.orders.index', [
            'orders' => $orders,
            'statuses' => self::STATUSES,
            'litigeCount' => Order::where('status', 'litige')->count(),
        ]);
    }
}
