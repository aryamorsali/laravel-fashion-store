<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductImageResource extends JsonResource
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
            'image' => $path ? asset(str_replace('\\', '/', $path)) : null,
        ];
    }
}
