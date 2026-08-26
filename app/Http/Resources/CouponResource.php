<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CouponResource extends JsonResource
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
            'code' => $this->code,
            'amount' => $this->amount,
            'discount_ceiling' => $this->discount_ceiling,
            'amount_type' => $this->amount_type,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ];
    }
}
