<?php

namespace App\Http\Resources;

use App\Models\PremiumSubscription;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin PremiumSubscription */
class PremiumSubscriptionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'tier' => $this->tier,
            'tier_label' => $this->tierLabel(),
            'price' => $this->price,
            'devise' => $this->devise,
            'status' => $this->status,
            'rejection_reason' => $this->rejection_reason,
            'requested_at' => $this->requested_at,
            'reviewed_at' => $this->reviewed_at,
            'expires_at' => $this->expires_at,
            'shop' => $this->whenLoaded('shop', fn () => [
                'id' => $this->shop->id,
                'name' => $this->shop->name,
            ]),
        ];
    }
}
