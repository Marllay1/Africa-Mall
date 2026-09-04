<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Order */
class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'total' => $this->total,
            'devise' => $this->devise,
            'shop' => [
                'id' => $this->shop->id,
                'name' => $this->shop->name,
            ],
            'items' => $this->whenLoaded('items', fn () => $this->items->map(fn ($item) => [
                'product_id' => $item->product_id,
                'product_name' => $item->product?->name,
                'quantity' => $item->quantity,
                'unit_price' => $item->unit_price,
            ])),
            'payment' => $this->whenLoaded('payment', fn () => $this->payment ? [
                'method' => $this->payment->method,
                'status' => $this->payment->status,
            ] : null),
            'created_at' => $this->created_at,
        ];
    }
}
