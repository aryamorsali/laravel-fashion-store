<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductCategoryResource extends JsonResource
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
            'description' => $this->description,
            'slug' => $this->slug,
            'image' => $path ? asset(str_replace('\\', '/', $path)) : null,
            'parent_id' => $this->parent_id,
        ];
    }
}
