<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VisitorStatsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'today' => $this['today'] ?? 0,
            'this_month' => $this['this_month'] ?? 0,
            'total' => $this['total'] ?? 0,
        ];
    }
}
