<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantResource extends JsonResource
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
            'price' => (float) $this->price,
            'product' => new ProductResource($this->whenLoaded('product')),
            'color' => new ColorResource($this->whenLoaded('color')),
            'size' => new SizeResource($this->whenLoaded('size')),
            'amazing_sale' => new AmazingSaleResource($this->whenLoaded('activeAmazingSale')),
            'final_price' => (float) $this->final_price,
            'stock' => $this->availableStock(),
        ];
    }
}
