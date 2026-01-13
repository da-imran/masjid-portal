<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class KutipanMasjidResource extends JsonResource
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
            'day' => $this->day,
            'day_name_ms' => $this->day_name_ms,
            'week' => $this->week,
            'month' => $this->month,
            'month_name_ms' => $this->month_name_ms,
            'year' => $this->year,
            'day_total' => (float) $this->day_total,
            'week_total' => (float) $this->week_total,
            'month_total' => (float) $this->month_total,
            'year_total' => (float) $this->year_total,
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
