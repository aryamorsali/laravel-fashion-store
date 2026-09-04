<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'product_variant_id' => $this->product_variant_id,
            'amazing_sale_id' => $this->amazing_sale_id,
            'quantity' => (int) $this->quantity,
            'product_snapshot' => $this->product_snapshot,
            'amazing_sale_snapshot' => $this->amazing,
            'amazing_sale_discount_amount' => (int) $this->amazing_sale_discount_amount,
            'final_product_price' => (int) $this->final_product_price,
            'final_total_price' => (int) $this->final_total_price,
        ];
    }
}
