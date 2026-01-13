<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BeritaSemasaResource extends JsonResource
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
            'title_ms' => $this->title_ms,
            'title_en' => $this->title_en,
            'title' => $this->title,
            'description_ms' => $this->description_ms,
            'description_en' => $this->description_en,
            'description' => $this->description,
            'content_ms' => $this->content_ms,
            'content_en' => $this->content_en,
            'content' => $this->content,
            'image_url' => $this->image_name ? asset('images/berita/' . $this->image_name) : null,
            'image_name' => $this->image_name,
            'view_count' => $this->view_count,
            'is_active' => $this->is_active,
            'is_featured' => $this->is_featured,
            'published_at' => $this->published_at?->toIso8601String(),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
