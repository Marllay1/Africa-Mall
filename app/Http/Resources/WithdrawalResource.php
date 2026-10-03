<?php

namespace App\Http\Resources;

use App\Models\Shop;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Withdrawal */
class WithdrawalResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'amount' => $this->amount,
            'devise' => $this->devise,
            'method' => $this->method,
            'method_label' => Shop::WITHDRAWAL_DESTINATION_LABELS[$this->method] ?? $this->method,
            'destination' => $this->destination,
            'status' => $this->status,
            'rejection_reason' => $this->rejection_reason,
            'processed_at' => $this->processed_at,
            'created_at' => $this->created_at,
            'shop' => $this->whenLoaded('shop', fn () => [
                'id' => $this->shop->id,
                'name' => $this->shop->name,
            ]),
        ];
    }
}
