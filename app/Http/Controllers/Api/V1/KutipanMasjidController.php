<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\KutipanMasjidResource;
use App\Models\KutipanMasjid;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class KutipanMasjidController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = KutipanMasjid::query();

        // Filter by month and year
        if ($request->has('month') && $request->has('year')) {
            $query->byMonth($request->input('month'), $request->input('year'));
        }

        // Filter by year
        if ($request->has('year')) {
            $query->byYear($request->input('year'));
        }

        // Order by date
        $query->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->orderBy('day', 'desc');

        // Pagination
        $perPage = min($request->input('per_page', 31), 100);

        $kutipan = $query->paginate($perPage);

        return KutipanMasjidResource::collection($kutipan);
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
     * Get current month collections.
     */
    public function currentMonth(): AnonymousResourceCollection
    {
        $kutipan = KutipanMasjid::currentMonth()
            ->orderBy('day')
            ->get();

        return KutipanMasjidResource::collection($kutipan);
    }
}
