<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserOrdersResource extends JsonResource
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
                'total_discount' => (int) $this->order_discount_amount,
                'delivery_amount' => (int) $this->delivery_amount,
            ],
            'items_count' => $this->whenCounted('orderItems'),
            'delivery_date' => $this->delivery_date,
            'created_at' => $this->created_at,
        ];
    }
}
