<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $shop = $request->user()->sellerProfile->shop;

        return view('seller.dashboard', [
            'shop' => $shop,
            'totalRevenue' => (int) $shop->orders()->where('status', '!=', 'cancelled')->sum('total'),
            'activeProductsCount' => $shop->products()->where('is_active', true)->count(),
            'ordersCount' => $shop->orders()->count(),
            'customersCount' => $shop->orders()->distinct('user_id')->count('user_id'),
            'recentProducts' => $shop->products()->latest()->take(5)->get(),
            'recentOrders' => $shop->orders()->with('user')->latest()->take(3)->get(),
        ]);
    }
}
