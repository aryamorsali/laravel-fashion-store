<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'status' => [
                'order_status' => $this->order_status,
                'payment_status' => $this->payment_status,
                'delivery_status' => $this->delivery_status,
            ],
            'pricing' => [
                'final_amount' => (int) $this->order_final_amount,
                'total_products_discount' => (int) $this->order_total_products_discount_amount,
                'total_discount' => (int) $this->order_discount_amount,
                'coupon_discount' => (int) $this->order_coupon_discount_amount,
                'common_discount' => (int) $this->order_common_discount_amount,
                'delivery_amount' => (int) $this->delivery_amount,
            ],
            'snapshots' => [
                'address' => $this->address_snapshot,
                'delivery' => $this->delivery_snapshot,
                'coupon' => $this->coupon_snapshot,
                'common_discount' => $this->common_discount_snapshot,
            ],
            'delivery_date' => $this->delivery_date,
            'created_at' => $this->created_at,

            'order_items' => OrderItemResource::collection($this->whenLoaded('orderItems')),
        ];
    }
}
