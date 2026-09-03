<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $path = data_get($this->image, 'indexArray.' . data_get($this->image, 'currentImage'));
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'image' => $path ? asset(str_replace('\\', '/', $path)) : null,
            'category' => new ProductCategoryResource($this->whenLoaded('productCategory')),
            'brand' => new BrandResource($this->whenLoaded('brand')),
            'is_liked' => $this->isLikedByUser(),
            'total_sold' => (int) ($this->total_sold ?? 0),
            'matched_variants' =>  $this->representativeVariant ? new ProductVariantResource($this->representativeVariant['variant']) : null,
        ];
    }
}
