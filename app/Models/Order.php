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

            if ($order->wasChanged('status') && $order->status === 'delivered' && ! $order->shopTransactions()->where('type', 'commission')->exists()) {
                $percent = PlatformSetting::current()->commission_percent;

                $transaction = new ShopTransaction([
                    'type' => 'commission',
                    'amount' => (int) round($order->total * $percent / 100),
                    'devise' => $order->devise,
                ]);
                $transaction->shop_id = $order->shop_id;
                $transaction->order_id = $order->id;
                $transaction->save();
            }

            if ($order->wasChanged('status') && $order->status === 'remboursee' && ! $order->shopTransactions()->where('type', 'refund')->exists()) {
                $transaction = new ShopTransaction([
                    'type' => 'refund',
                    'amount' => $order->total,
                    'devise' => $order->devise,
                ]);
                $transaction->shop_id = $order->shop_id;
                $transaction->order_id = $order->id;
                $transaction->save();
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

    public function shopTransactions(): HasMany
    {
        return $this->hasMany(ShopTransaction::class);
    }
}
