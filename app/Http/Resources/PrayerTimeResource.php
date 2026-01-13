<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrayerTimeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $data = $this->resource;

        return [
            'date' => $data['date'] ?? now()->format('Y-m-d'),
            'hijri' => $data['hijri'] ?? null,
            'imsak' => $data['imsak'] ?? null,
            'subuh' => $data['subuh'] ?? null,
            'syuruk' => $data['syuruk'] ?? null,
            'zohor' => $data['zohor'] ?? null,
            'asar' => $data['asar'] ?? null,
            'maghrib' => $data['maghrib'] ?? null,
            'isyak' => $data['isyak'] ?? null,
            'zone' => $data['zone'] ?? 'PNG01',
        ];
    }
}
