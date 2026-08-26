<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CommentResource extends JsonResource
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
            'body' => $this->body,
            'rating' => $this->rating,
            'approved' => (bool) $this->approved,
            'parent_id' => $this->parent_id,
            'created_at' => $this->created_at,
            'user' => new CommentAuthorResource(
                $this->whenLoaded('user')
            ),

            'children' => CommentResource::collection(
                $this->whenLoaded('children')
            ),
        ];
    }
}
