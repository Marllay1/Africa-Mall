<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'slug', 'description', 'price', 'discount_price', 'devise', 'stock', 'image_url', 'is_active'])]
class Product extends Model
{
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
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
}
