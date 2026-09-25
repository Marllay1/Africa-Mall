<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['tier', 'price', 'devise', 'status'])]
class PremiumSubscription extends Model
{
    public const TIERS = [
        'basic' => ['label' => 'Basique', 'price' => 5000],
        'pro' => ['label' => 'Pro', 'price' => 15000],
    ];

    protected function casts(): array
    {
        return [
            'requested_at' => 'datetime',
            'reviewed_at' => 'datetime',
            'expires_at' => 'datetime',
        ];
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function tierLabel(): string
    {
        return self::TIERS[$this->tier]['label'] ?? $this->tier;
    }
}
