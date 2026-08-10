<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommonDiscountResource extends JsonResource
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
            'title' => $this->title,
            'percentage' => $this->percentage,
            'discount_ceiling' => $this->discount_ceiling,
            'minimal_order_amount' => $this->minimal_order_amount,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'percentage' => $this->percentage,

        ];
    }
}
