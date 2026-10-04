<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\PlatformSetting;
use App\Models\Shop;
use App\Models\Withdrawal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class FinanceController extends Controller
{
    public function revenues(Request $request): View
    {
        $shop = $this->shop($request);
        $balance = $shop->financeBalance();

        return view('seller.finances.revenues', [
            'shop' => $shop,
            'balance' => $balance,
            'minWithdrawal' => PlatformSetting::current()->min_withdrawal_amount,
            'transactions' => $shop->paginatedTransactions(),
            'withdrawals' => $shop->withdrawals()->latest()->take(10)->get(),
            'payoutMethodLabel' => Shop::WITHDRAWAL_DESTINATION_LABELS[$shop->sellerProfile->payment_mode] ?? $shop->sellerProfile->payment_mode,
        ]);
    }

    public function statistics(Request $request): View
    {
        $shop = $this->shop($request);

        $months = collect(range(5, 0))->map(fn (int $i) => now()->subMonths($i)->startOfMonth());

        $orders = $shop->orders()
            ->where('created_at', '>=', $months->first())
            ->get(['status', 'total', 'created_at']);

        $revenueByMonth = $months->map(function (Carbon $month) use ($orders) {
            return $orders
                ->where('status', '!=', 'cancelled')
                ->filter(fn ($order) => $order->created_at->isSameMonth($month))
                ->sum('total');
        });

        $ordersByMonth = $months->map(function (Carbon $month) use ($orders) {
            return $orders->filter(fn ($order) => $order->created_at->isSameMonth($month))->count();
        });

        $isPremium = $shop->isPremium();

        return view('seller.finances.statistics', [
            'monthLabels' => $months->map(fn (Carbon $month) => $month->translatedFormat('M Y')),
            'revenueByMonth' => $revenueByMonth,
            'ordersByMonth' => $ordersByMonth,
            'deliveredCount' => $orders->where('status', 'delivered')->count(),
            'cancelledCount' => $orders->where('status', 'cancelled')->count(),
            'averageOrderValue' => $orders->count() > 0 ? (int) round($orders->avg('total')) : 0,
            'isPremium' => $isPremium,
            'topViewedProducts' => $isPremium
                ? $shop->products()->orderByDesc('views_count')->take(5)->get()
                : collect(),
            'totalViews' => $isPremium ? (int) $shop->products()->sum('views_count') : 0,
        ]);
    }

    public function requestWithdrawal(Request $request): RedirectResponse
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

        return back()->with('status', 'withdrawal-requested');
    }

    private function shop(Request $request): Shop
    {
        return $request->user()->sellerProfile->shop;
    }
}
