<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class DashboardController extends Controller
{
    private const PERIODS = ['7j' => 7, '30j' => 30, '90j' => 90];

    public function index(Request $request): View
    {
        $shop = $request->user()->sellerProfile->shop;

        $period = $request->query('period', '30j');
        $days = self::PERIODS[$period] ?? self::PERIODS['30j'];

        $currentStart = now()->subDays($days);
        $previousStart = now()->subDays($days * 2);

        $currentOrders = $shop->orders()->where('created_at', '>=', $currentStart)->get(['id', 'user_id', 'status', 'total', 'created_at']);
        $previousOrders = $shop->orders()->whereBetween('created_at', [$previousStart, $currentStart])->get(['id', 'user_id', 'status', 'total', 'created_at']);

        $currentRevenue = (int) $currentOrders->where('status', '!=', 'cancelled')->sum('total');
        $previousRevenue = (int) $previousOrders->where('status', '!=', 'cancelled')->sum('total');

        $newProductsCurrent = $shop->products()->where('created_at', '>=', $currentStart)->count();
        $newProductsPrevious = $shop->products()->whereBetween('created_at', [$previousStart, $currentStart])->count();

        $newCustomersCurrent = $this->newCustomers($shop, $currentOrders, $currentStart);
        $newCustomersPrevious = $this->newCustomers($shop, $previousOrders, $previousStart);

        return view('seller.dashboard', [
            'shop' => $shop,
            'period' => $period,
            'periods' => array_keys(self::PERIODS),

            'totalRevenue' => $currentRevenue,
            'revenueVariation' => $this->variation($currentRevenue, $previousRevenue),

            'activeProductsCount' => $shop->products()->where('is_active', true)->count(),
            'pendingProductsCount' => $shop->products()->where('is_active', false)->count(),
            'outOfStockProductsCount' => $shop->products()->where('stock', 0)->count(),
            'newProductsVariation' => $this->variation($newProductsCurrent, $newProductsPrevious),

            'ordersCount' => $currentOrders->count(),
            'ordersVariation' => $this->variation($currentOrders->count(), $previousOrders->count()),
            'pendingOrdersCount' => $shop->orders()->where('status', 'pending')->count(),
            'toShipOrdersCount' => $shop->orders()->whereIn('status', ['confirmed', 'preparation'])->count(),

            'customersCount' => $shop->orders()->whereNotNull('user_id')->distinct('user_id')->count('user_id'),
            'newCustomersCount' => $newCustomersCurrent,
            'newCustomersVariation' => $this->variation($newCustomersCurrent, $newCustomersPrevious),

            'recentProducts' => $shop->products()->latest()->take(5)->get(),
            'recentOrders' => $shop->orders()->with('user')->latest()->take(3)->get(),
        ]);
    }

    /**
     * Customers who placed their first-ever order with this shop during the given window.
     */
    private function newCustomers(Shop $shop, Collection $windowOrders, Carbon $windowStart): int
    {
        $customerIds = $windowOrders->pluck('user_id')->filter()->unique();

        if ($customerIds->isEmpty()) {
            return 0;
        }

        $returningIds = $shop->orders()
            ->whereIn('user_id', $customerIds)
            ->where('created_at', '<', $windowStart)
            ->distinct('user_id')
            ->pluck('user_id');

        return $customerIds->diff($returningIds)->count();
    }

    private function variation(int $current, int $previous): ?float
    {
        if ($previous === 0) {
            return $current > 0 ? 100.0 : null;
        }

        return round(($current - $previous) / $previous * 100, 1);
    }
}
