<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PrayerTimeResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class PrayerTimeController extends BaseController
{
    /**
     * The prayer times API URL.
     */
    private string $apiUrl = 'https://api.waktusolat.app/v2/solat';

    /**
     * Get prayer times for today.
     */
    public function today(Request $request): PrayerTimeResource|JsonResponse
    {
        $zone = $request->input('zone', 'PNG01');
        $cacheKey = "prayer_times_{$zone}_" . now()->format('Y-m-d');

        $prayerTimes = Cache::remember($cacheKey, 86400, function () use ($zone) {
            return $this->fetchPrayerTimes($zone);
        });

        if (!$prayerTimes) {
            return $this->error('Failed to fetch prayer times', 500);
        }

        return new PrayerTimeResource($prayerTimes);
    }

    /**
     * Get prayer times for the month.
     */
    public function month(Request $request): JsonResponse
    {
        $zone = $request->input('zone', 'PNG01');
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);

        $cacheKey = "prayer_times_month_{$zone}_{$year}_{$month}";

        $prayerTimes = Cache::remember($cacheKey, 86400, function () use ($zone, $year, $month) {
            return $this->fetchPrayerTimesByMonth($zone, $year, $month);
        });

        if (!$prayerTimes) {
            return $this->error('Failed to fetch prayer times', 500);
        }

        return $this->success($prayerTimes, 'Monthly prayer times retrieved successfully');
    }

    /**
     * Fetch prayer times from external API.
     */
    private function fetchPrayerTimes(string $zone): ?array
    {
        try {
            $now = now();
            $response = Http::timeout(10)->get("{$this->apiUrl}/{$zone}", [
                'query' => [
                    'year' => $now->year,
                    'month' => $now->month,
                ],
            ]);

            if ($response->successful()) {
                $data = $response->json();

                // Save to cache file for backup
                $this->saveToCacheFile($zone, $data);

                return $this->formatPrayerTimes($data);
            }
        } catch (\Exception $e) {
            // Log error
            \Log::error('Failed to fetch prayer times: ' . $e->getMessage());
        }

        // Try to get from backup file
        return $this->getFromCacheFile($zone);
    }

    /**
     * Fetch prayer times for a specific month.
     */
    private function fetchPrayerTimesByMonth(string $zone, int $year, int $month): ?array
    {
        try {
            $response = Http::timeout(10)->get("{$this->apiUrl}/{$zone}", [
                'query' => [
                    'year' => $year,
                    'month' => $month,
                ],
            ]);

            if ($response->successful()) {
                $data = $response->json();

                return $this->formatMonthlyPrayerTimes($data);
            }
        } catch (\Exception $e) {
            \Log::error('Failed to fetch monthly prayer times: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Format prayer times for API response (today's prayer times).
     */
    private function formatPrayerTimes(array $data): ?array
    {
        if (!isset($data['prayers']) || !is_array($data['prayers'])) {
            return null;
        }

        $today = now()->day;

        // Find today's prayer times
        foreach ($data['prayers'] as $item) {
            if (($item['day'] ?? null) === $today) {
                return [
                    'date' => now()->format('Y-m-d'),
                    'hijri' => $item['hijri'] ?? null,
                    'imsak' => $this->timestampToTime($item['imsak'] ?? null),
                    'subuh' => $this->timestampToTime($item['fajr'] ?? null),
                    'syuruk' => $this->timestampToTime($item['syuruk'] ?? null),
                    'zohor' => $this->timestampToTime($item['dhuhr'] ?? null),
                    'asar' => $this->timestampToTime($item['asr'] ?? null),
                    'maghrib' => $this->timestampToTime($item['maghrib'] ?? null),
                    'isyak' => $this->timestampToTime($item['isha'] ?? null),
                    'zone' => $data['zone'] ?? 'PNG01',
                ];
            }
        }

        return null;
    }

    /**
     * Format monthly prayer times for API response.
     */
    private function formatMonthlyPrayerTimes(array $data): ?array
    {
        if (!isset($data['prayers']) || !is_array($data['prayers'])) {
            return null;
        }

        $formatted = [];
        foreach ($data['prayers'] as $item) {
            $formatted[] = [
                'day' => $item['day'] ?? null,
                'date' => ($data['year'] ?? now()->year) . '-' . ($data['month_number'] ?? now()->month) . '-' . str_pad($item['day'] ?? 1, 2, '0', STR_PAD_LEFT),
                'hijri' => $item['hijri'] ?? null,
                'imsak' => $this->timestampToTime($item['imsak'] ?? null),
                'subuh' => $this->timestampToTime($item['fajr'] ?? null),
                'syuruk' => $this->timestampToTime($item['syuruk'] ?? null),
                'zohor' => $this->timestampToTime($item['dhuhr'] ?? null),
                'asar' => $this->timestampToTime($item['asr'] ?? null),
                'maghrib' => $this->timestampToTime($item['maghrib'] ?? null),
                'isyak' => $this->timestampToTime($item['isha'] ?? null),
            ];
        }

        return $formatted;
    }

    /**
     * Convert Unix timestamp to time string (HH:MM).
     */
    private function timestampToTime($timestamp): ?string
    {
        if ($timestamp === null) {
            return null;
        }

        // The API returns timestamps in milliseconds, convert to seconds
        $seconds = $timestamp > 9999999999 ? $timestamp / 1000 : $timestamp;

        return date('H:i', $seconds);
    }

    /**
     * Save prayer times to cache file.
     */
    private function saveToCacheFile(string $zone, array $data): void
    {
        $filename = public_path("api_data/waktusolat_" . now()->format('m') . ".json");
        $currentData = [];

        if (file_exists($filename)) {
            $currentData = json_decode(file_get_contents($filename), true) ?? [];
        }

        $currentData[$zone] = $data;
        file_put_contents($filename, json_encode($currentData, JSON_PRETTY_PRINT));
    }

    /**
     * Get prayer times from cache file.
     */
    private function getFromCacheFile(string $zone): ?array
    {
        $filename = public_path("api_data/waktusolat_" . now()->format('m') . ".json");

        if (!file_exists($filename)) {
            return null;
        }

        $data = json_decode(file_get_contents($filename), true);

        if (isset($data[$zone])) {
            return $this->formatPrayerTimes($data[$zone]);
        }

        return null;
    }
}
