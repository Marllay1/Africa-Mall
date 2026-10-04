<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Controller;
use App\Models\PlatformSetting;
use App\Models\Shop;
use App\Models\Withdrawal;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class FinanceController extends Controller
{
    public function revenues(Request $request): JsonResponse
    {
        $shop = $this->shop($request);

        return response()->json([
            'balance' => $shop->financeBalance(),
            'transactions' => $shop->paginatedTransactions(),
            'withdrawals' => $shop->withdrawals()->latest()->take(10)->get(),
            'payout_method_label' => Shop::WITHDRAWAL_DESTINATION_LABELS[$shop->sellerProfile->payment_mode] ?? $shop->sellerProfile->payment_mode,
        ]);
    }

    public function statistics(Request $request): JsonResponse
    {
        $shop = $this->shop($request);

        $months = collect(range(5, 0))->map(fn (int $i) => now()->subMonths($i)->startOfMonth());

        $orders = $shop->orders()
            ->where('created_at', '>=', $months->first())
            ->get(['status', 'total', 'created_at']);

        $revenueByMonth = $months->map(fn (Carbon $month) => $orders
            ->where('status', '!=', 'cancelled')
            ->filter(fn ($order) => $order->created_at->isSameMonth($month))
            ->sum('total'));

        $ordersByMonth = $months->map(fn (Carbon $month) => $orders
            ->filter(fn ($order) => $order->created_at->isSameMonth($month))
            ->count());

        $isPremium = $shop->isPremium();

        return response()->json([
            'month_labels' => $months->map(fn (Carbon $month) => $month->translatedFormat('M Y')),
            'revenue_by_month' => $revenueByMonth,
            'orders_by_month' => $ordersByMonth,
            'delivered_count' => $orders->where('status', 'delivered')->count(),
            'cancelled_count' => $orders->where('status', 'cancelled')->count(),
            'average_order_value' => $orders->count() > 0 ? (int) round($orders->avg('total')) : 0,
            'is_premium' => $isPremium,
            'top_viewed_products' => $isPremium
                ? $shop->products()->orderByDesc('views_count')->take(5)->get(['id', 'name', 'views_count'])
                : [],
            'total_views' => $isPremium ? (int) $shop->products()->sum('views_count') : 0,
        ]);
    }

    public function requestWithdrawal(Request $request): JsonResponse
    {
        $shop = $this->shop($request);
        $balance = $shop->financeBalance();
        $minWithdrawal = PlatformSetting::current()->min_withdrawal_amount;

        $validated = $request->validate([
            'amount' => ['required', 'integer', 'min:'.$minWithdrawal, 'max:'.max($balance['available'], $minWithdrawal)],
        ]);

        $profile = $shop->sellerProfile;

        $destination = match ($profile->payment_mode) {
            'orange' => $profile->numero_om,
            'paypal' => $profile->email_paypal,
            'banque' => trim($profile->nom_banque.' — '.$profile->numero_compte.' ('.$profile->titulaire_compte.')'),
            default => '—',
        };

        $withdrawal = new Withdrawal([
            'amount' => $validated['amount'],
            'devise' => $profile->devise,
            'method' => $profile->payment_mode,
            'destination' => $destination,
            'status' => 'pending',
        ]);
        $withdrawal->shop_id = $shop->id;
        $withdrawal->save();

        return response()->json(['message' => 'Retrait demandé.'], 201);
    }

    private function shop(Request $request): Shop
    {
        return $request->user()->sellerProfile->shop;
    }
}
