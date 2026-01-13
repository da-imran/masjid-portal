<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CorporateInfoResource extends JsonResource
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
            'slug' => $this->slug,
            'title_ms' => $this->title_ms,
            'title_en' => $this->title_en,
            'title' => $this->title,
            'content_ms' => $this->content_ms,
            'content_en' => $this->content_en,
            'content' => $this->content,
            'image_url' => $this->image_name ? asset('images/' . $this->image_name) : null,
            'image_name' => $this->image_name,
            'order_column' => $this->order_column,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
