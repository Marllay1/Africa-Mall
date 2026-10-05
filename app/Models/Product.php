<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'slug', 'description', 'price', 'discount_price', 'devise', 'stock', 'image_url', 'is_active', 'weight_kg', 'length_cm', 'width_cm', 'height_cm'])]
class Product extends Model
{
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'weight_kg' => 'float',
            'length_cm' => 'float',
            'width_cm' => 'float',
            'height_cm' => 'float',
        ];
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'subcategory_id');
    }

    public function variants(): HasMany
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function hasDimensions(): bool
    {
        return $this->weight_kg !== null || $this->length_cm !== null || $this->width_cm !== null || $this->height_cm !== null;
    }

    public function formattedWeight(): ?string
    {
        if ($this->weight_kg === null) {
            return null;
        }

        return rtrim(rtrim(number_format($this->weight_kg, 3, ',', ' '), '0'), ',');
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('position');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class)->latest();
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function averageRating(): ?float
    {
        $average = $this->reviews()->avg('rating');

        return $average !== null ? round($average, 1) : null;
    }

    public function reviewsCount(): int
    {
        return $this->reviews()->count();
    }

    public function salesCount(): int
    {
        return (int) $this->orderItems()
            ->whereHas('order', fn ($query) => $query->where('status', '!=', 'cancelled'))
            ->sum('quantity');
    }

    public function isFavoritedBy(?User $user): bool
    {
        return $user !== null && $this->favorites()->where('user_id', $user->id)->exists();
    }

    public function hasBeenPurchasedBy(?User $user): bool
    {
        return $user !== null && $this->orderItems()
            ->whereHas('order', fn ($query) => $query->where('user_id', $user->id)->where('status', '!=', 'cancelled'))
            ->exists();
    }

    public function hasBeenReviewedBy(?User $user): bool
    {
        return $user !== null && $this->reviews()->where('user_id', $user->id)->exists();
    }

    public function effectivePrice(): int
    {
        return $this->discount_price ?? $this->price;
    }

    /**
     * Order products from a Premium shop first, ranked by tier (Pro before Basique).
     */
    public function scopePremiumFirst(Builder $query): Builder
    {
        $case = collect(array_keys(PremiumSubscription::TIERS))
            ->map(fn (string $tier, int $rank) => "when '{$tier}' then ".($rank + 1))
            ->implode(' ');

        return $query->orderByRaw(
            "coalesce((select case tier {$case} else 0 end
                from premium_subscriptions
                where premium_subscriptions.shop_id = products.shop_id
                  and premium_subscriptions.status = ?
                  and (premium_subscriptions.expires_at is null or premium_subscriptions.expires_at > ?)
                order by case tier {$case} else 0 end desc
                limit 1), 0) desc",
            ['active', now()]
        );
    }
}
