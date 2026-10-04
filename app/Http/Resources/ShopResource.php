<?php

namespace App\Http\Resources;

use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Shop */
class ShopResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'logo_path' => $this->logo_path,
            'is_premium' => $this->isPremium(),
            'premium_tier_label' => $this->premiumTierLabel(),
            'seller' => $this->whenLoaded('sellerProfile', fn () => [
                'id' => $this->sellerProfile->user_id,
                'name' => $this->sellerProfile->user?->name,
                'email' => $this->sellerProfile->user?->email,
            ]),
            'products_count' => $this->whenCounted('products'),
            'orders_count' => $this->whenCounted('orders'),
        ];
    }
}
