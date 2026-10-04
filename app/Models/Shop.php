<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Pagination\LengthAwarePaginator;

#[Fillable(['name', 'slug', 'description', 'logo_path'])]
class Shop extends Model
{
    public const WITHDRAWAL_DESTINATION_LABELS = [
        'orange' => 'Orange Money / MTN Money',
        'paypal' => 'PayPal',
        'banque' => 'Compte bancaire',
    ];

    public function sellerProfile(): BelongsTo
    {
        return $this->belongsTo(SellerProfile::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class);
    }

    public function withdrawals(): HasMany
    {
        return $this->hasMany(Withdrawal::class);
    }

    public function premiumSubscriptions(): HasMany
    {
        return $this->hasMany(PremiumSubscription::class);
    }

    public function advertisements(): HasMany
    {
        return $this->hasMany(Advertisement::class);
    }

    public function activePremiumSubscriptions(): HasMany
    {
        return $this->premiumSubscriptions()
            ->where('status', 'active')
            ->where(fn ($query) => $query->whereNull('expires_at')->orWhere('expires_at', '>', now()));
    }

    public function isPremium(): bool
    {
        return $this->relationLoaded('activePremiumSubscriptions')
            ? $this->activePremiumSubscriptions->isNotEmpty()
            : $this->activePremiumSubscriptions()->exists();
    }

    public function premiumTierLabel(): ?string
    {
        $subscription = $this->relationLoaded('activePremiumSubscriptions')
            ? $this->activePremiumSubscriptions->first()
            : $this->activePremiumSubscriptions()->first();

        return $subscription?->tierLabel();
    }

    /**
     * @return array{total: int, available: int, pending: int, withdrawn: int}
     */
    public function financeBalance(): array
    {
        $delivered = (int) $this->orders()->where('status', 'delivered')->sum('total');
        $inProgress = (int) $this->orders()->whereNotIn('status', ['delivered', 'cancelled'])->sum('total');
        $paidOut = (int) $this->withdrawals()->where('status', 'paid')->sum('amount');
        $pendingWithdrawals = (int) $this->withdrawals()->where('status', 'pending')->sum('amount');

        return [
            'total' => $delivered + $inProgress,
            'available' => max(0, $delivered - $paidOut - $pendingWithdrawals),
            'pending' => $inProgress,
            'withdrawn' => $paidOut,
        ];
    }

    /**
     * Merge payments and withdrawals into a single date-sorted, paginated transaction feed.
     * Both sources are small per shop, so the merge happens in PHP rather than a portable
     * SQL UNION across the two tables.
     */
    public function paginatedTransactions(int $perPage = 15): LengthAwarePaginator
    {
        $page = LengthAwarePaginator::resolveCurrentPage();

        $all = collect()
            ->concat(
                Payment::whereHas('order', fn ($query) => $query->where('shop_id', $this->id))
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
                $this->withdrawals()->get()
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
            ->values();

        return new LengthAwarePaginator(
            $all->forPage($page, $perPage)->values(),
            $all->count(),
            $perPage,
            $page,
        );
    }
}
