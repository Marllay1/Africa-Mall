<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Models\PremiumSubscription;
use App\Models\Product;
use App\Models\SellerProfile;
use App\Models\User;
use App\Models\Withdrawal;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'usersCount' => User::count(),
            'activeSellersCount' => SellerProfile::where('status', 'active')->count(),
            'pendingSellerRequestsCount' => SellerProfile::where('status', 'pending')->count(),
            'productsCount' => Product::count(),
            'activeProductsCount' => Product::where('is_active', true)->count(),
            'ordersCount' => Order::count(),
            'ordersByStatus' => Order::selectRaw('status, count(*) as total')->groupBy('status')->pluck('total', 'status'),
            'platformRevenue' => (int) Order::where('status', 'delivered')->sum('total'),
            'transactionsCount' => Payment::count() + Withdrawal::count(),
            'recentActivity' => $this->recentActivity(),
        ]);
    }

    private function recentActivity()
    {
        $sellerRequests = SellerProfile::with('user')->latest('submitted_at')->take(10)->get()
            ->map(fn (SellerProfile $profile) => [
                'label' => __('Demande vendeur : :shop (:status)', ['shop' => $profile->shop_name, 'status' => $profile->status]),
                'date' => $profile->submitted_at,
            ]);

        $premiumRequests = PremiumSubscription::with('shop')->latest('requested_at')->take(10)->get()
            ->map(fn (PremiumSubscription $subscription) => [
                'label' => __('Demande Premium : :shop (:status)', ['shop' => $subscription->shop?->name, 'status' => $subscription->status]),
                'date' => $subscription->requested_at,
            ]);

        $withdrawals = Withdrawal::with('shop')->latest()->take(10)->get()
            ->map(fn (Withdrawal $withdrawal) => [
                'label' => __('Retrait : :shop (:status)', ['shop' => $withdrawal->shop?->name, 'status' => $withdrawal->status]),
                'date' => $withdrawal->created_at,
            ]);

        return $sellerRequests->concat($premiumRequests)->concat($withdrawals)
            ->sortByDesc('date')
            ->take(10)
            ->values();
    }
}
