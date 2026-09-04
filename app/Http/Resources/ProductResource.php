<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Product */
class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => $this->price,
            'discount_price' => $this->discount_price,
            'effective_price' => $this->effectivePrice(),
            'devise' => $this->devise,
            'stock' => $this->stock,
            'image_url' => $this->image_url,
            'is_active' => $this->is_active,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'shop' => [
                'id' => $this->shop->id,
                'name' => $this->shop->name,
                'slug' => $this->shop->slug,
            ],
            'created_at' => $this->created_at,
        ];
    }
}
