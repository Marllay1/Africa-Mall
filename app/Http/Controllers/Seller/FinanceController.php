<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Shop;
use App\Models\Withdrawal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class FinanceController extends Controller
{
    private const WITHDRAWAL_DESTINATION_LABELS = [
        'orange' => 'Orange Money / MTN Money',
        'paypal' => 'PayPal',
        'banque' => 'Compte bancaire',
    ];

    public function revenues(Request $request): View
    {
        $shop = $this->shop($request);
        $balance = $this->balance($shop);

        $transactions = collect()
            ->concat(
                Payment::whereHas('order', fn ($query) => $query->where('shop_id', $shop->id))
                    ->latest()
                    ->take(30)
                    ->get()
                    ->map(fn (Payment $payment) => [
                        'type' => 'payment',
                        'label' => __('Paiement commande').' #AFR'.str_pad((string) $payment->order_id, 4, '0', STR_PAD_LEFT),
                        'amount' => $payment->amount,
                        'devise' => $payment->devise,
                        'status' => $payment->status,
                        'date' => $payment->created_at,
                    ])
            )
            ->concat(
                $shop->withdrawals()->latest()->take(30)->get()
                    ->map(fn (Withdrawal $withdrawal) => [
                        'type' => 'withdrawal',
                        'label' => __('Retrait vers').' '.(self::WITHDRAWAL_DESTINATION_LABELS[$withdrawal->method] ?? $withdrawal->method),
                        'amount' => $withdrawal->amount,
                        'devise' => $withdrawal->devise,
                        'status' => $withdrawal->status,
                        'date' => $withdrawal->created_at,
                    ])
            )
            ->sortByDesc('date')
            ->take(30)
            ->values();

        return view('seller.finances.revenues', [
            'shop' => $shop,
            'balance' => $balance,
            'transactions' => $transactions,
            'withdrawals' => $shop->withdrawals()->latest()->take(10)->get(),
            'payoutMethodLabel' => self::WITHDRAWAL_DESTINATION_LABELS[$shop->sellerProfile->payment_mode] ?? $shop->sellerProfile->payment_mode,
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

        return view('seller.finances.statistics', [
            'monthLabels' => $months->map(fn (Carbon $month) => $month->translatedFormat('M Y')),
            'revenueByMonth' => $revenueByMonth,
            'ordersByMonth' => $ordersByMonth,
            'deliveredCount' => $orders->where('status', 'delivered')->count(),
            'cancelledCount' => $orders->where('status', 'cancelled')->count(),
            'averageOrderValue' => $orders->count() > 0 ? (int) round($orders->avg('total')) : 0,
        ]);
    }

    public function requestWithdrawal(Request $request): RedirectResponse
    {
        $shop = $this->shop($request);
        $balance = $this->balance($shop);

        $validated = $request->validate([
            'amount' => ['required', 'integer', 'min:1', 'max:'.max($balance['available'], 1)],
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

    /**
     * @return array{total: int, available: int, pending: int, withdrawn: int}
     */
    private function balance(Shop $shop): array
    {
        $delivered = (int) $shop->orders()->where('status', 'delivered')->sum('total');
        $inProgress = (int) $shop->orders()->whereNotIn('status', ['delivered', 'cancelled'])->sum('total');
        $paidOut = (int) $shop->withdrawals()->where('status', 'paid')->sum('amount');
        $pendingWithdrawals = (int) $shop->withdrawals()->where('status', 'pending')->sum('amount');

        return [
            'total' => $delivered + $inProgress,
            'available' => max(0, $delivered - $paidOut - $pendingWithdrawals),
            'pending' => $inProgress,
            'withdrawn' => $paidOut,
        ];
    }

    private function shop(Request $request): Shop
    {
        return $request->user()->sellerProfile->shop;
    }
}
