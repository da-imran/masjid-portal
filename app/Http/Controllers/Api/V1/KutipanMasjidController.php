<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\KutipanMasjidResource;
use App\Models\KutipanMasjid;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class KutipanMasjidController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $query = KutipanMasjid::query();

        if ($request->has('month') && $request->has('year')) {
            $query->byMonth($request->input('month'), $request->input('year'));
        }

        if ($request->has('year')) {
            $query->byYear($request->input('year'));
        }

        $query->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->orderBy('week', 'desc')
            ->orderBy('day', 'asc');

        // Get all records without pagination
        $kutipan = $query->get();

        return $this->success(KutipanMasjidResource::collection($kutipan), 'Collections retrieved successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): KutipanMasjidResource|JsonResponse
    {
        $kutipan = KutipanMasjid::find($id);

        if (!$kutipan) {
            return $this->notFound('Collection record not found');
        }

        return new KutipanMasjidResource($kutipan);
    }

    /**
     * Get summary statistics.
     */
    public function summary(Request $request): JsonResponse
    {
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
        $summary = KutipanMasjid::getSummary($month, $year);

        return $this->success($summary, 'Summary retrieved successfully');
    }

    /**
     * Get comprehensive summaries (daily, weekly, monthly, yearly).
     */
    public function summaries(): JsonResponse
    {
        $kutipan = KutipanMasjid::orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->orderBy('week', 'desc')
            ->orderBy('day', 'asc')
            ->get();

        $daily = $kutipan->map(fn ($item) => [
            'year' => (int) $item->year,
            'month' => (int) $item->month,
            'monthName' => $item->month_name_ms,
            'week' => (int) $item->week,
            'day' => (int) $item->day,
            'dayName' => $item->day_name_ms,
            'dayTotal' => (float) $item->day_total,
        ]);

        $weeklyMap = [];
        foreach ($kutipan as $item) {
            $key = "{$item->year}-{$item->month}-{$item->week}";
            if (!isset($weeklyMap[$key])) {
                $weeklyMap[$key] = [
                    'year' => (int) $item->year,
                    'month' => (int) $item->month,
                    'monthName' => $item->month_name_ms,
                    'week' => (int) $item->week,
                    'weekTotal' => 0,
                ];
            }
            $weeklyMap[$key]['weekTotal'] += (float) $item->day_total;
        }

        $monthlyMap = [];
        foreach ($kutipan as $item) {
            $key = "{$item->year}-{$item->month}";
            if (!isset($monthlyMap[$key])) {
                $monthlyMap[$key] = [
                    'year' => (int) $item->year,
                    'month' => (int) $item->month,
                    'monthName' => $item->month_name_ms,
                    'monthTotal' => 0,
                ];
            }
            $monthlyMap[$key]['monthTotal'] += (float) $item->day_total;
        }

        $yearlyMap = [];
        foreach ($kutipan as $item) {
            if (!isset($yearlyMap[$item->year])) {
                $yearlyMap[$item->year] = [
                    'year' => (int) $item->year,
                    'yearTotal' => 0,
                ];
            }
            $yearlyMap[$item->year]['yearTotal'] += (float) $item->day_total;
        }

        return $this->success([
            'daily' => $daily->values(),
            'weekly' => array_values($weeklyMap),
            'monthly' => array_values($monthlyMap),
            'yearly' => array_values($yearlyMap),
        ], 'Summaries retrieved successfully');
    }

    /**
     * Get current month collections.
     */
    public function currentMonth(): JsonResponse
    {
        $kutipan = KutipanMasjid::currentMonth()
            ->orderBy('day')
            ->get();

        return $this->success(KutipanMasjidResource::collection($kutipan), 'Current month collections retrieved successfully');
    }
}
