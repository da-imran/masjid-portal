<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class FetchPrayerTimes implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public string $zone;

    public function __construct(string $zone = 'PNG01')
    {
        $this->zone = $zone;
    }

    public function handle(): void
    {
        $now = Carbon::now();

        $directory = 'api_data';
        $fileName = "waktusolat_{$now->format('Y_m')}.json";
        $filePath = "{$directory}/{$fileName}";

        Storage::makeDirectory($directory);

        $storedData = null;
        $shouldFetch = true;

        if (Storage::exists($filePath)) {
            $storedData = json_decode(Storage::get($filePath), true);

            if (
                isset($storedData['fetched_at']) &&
                Carbon::parse($storedData['fetched_at'])->diffInDays($now) < 14
            ) {
                $shouldFetch = false;
            }
        }

        if (!$shouldFetch) {
            return;
        }

        $response = Http::timeout(10)
            ->retry(3, 2000)
            ->get("https://api.waktusolat.app/v2/solat/{$this->zone}");

        if ($response->failed()) {
            Log::error('Prayer time API fetch failed', [
                'zone' => $this->zone,
                'status' => $response->status(),
            ]);
            return;
        }

        $apiData = $response->json();
        $prayers = $apiData['prayers'] ?? [];

        if (
            !$storedData ||
            json_encode($storedData['prayers']) !== json_encode($prayers)
        ) {
            Storage::put($filePath, json_encode([
                'fetched_at' => $now->toIso8601String(),
                'zone' => $this->zone,
                'prayers' => $prayers,
            ], JSON_PRETTY_PRINT));
        }
    }
}
