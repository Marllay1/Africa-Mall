<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['status', 'total', 'devise', 'guest_name', 'guest_phone', 'guest_address', 'coupon_code', 'discount_amount', 'delivery_address'])]
class Order extends Model
{
    protected static function booted(): void
    {
        static::updated(function (Order $order): void {
            if ($order->wasChanged('status') && $order->status === 'litige' && ! $order->dispute()->exists()) {
                $dispute = new Dispute(['status' => 'open']);
                $dispute->order_id = $order->id;
                $dispute->save();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function dispute(): HasOne
    {
        return $this->hasOne(Dispute::class);
    }
}
