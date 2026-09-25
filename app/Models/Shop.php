<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'slug', 'description', 'logo_path'])]
class Shop extends Model
{
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
}
