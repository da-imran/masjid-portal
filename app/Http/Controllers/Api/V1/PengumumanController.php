<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PengumumanResource;
use App\Models\Pengumuman;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PengumumanController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Pengumuman::active()->valid();

        // Search functionality
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title_ms', 'like', "%{$search}%")
                    ->orWhere('title_en', 'like', "%{$search}%")
                    ->orWhere('description_ms', 'like', "%{$search}%")
                    ->orWhere('description_en', 'like', "%{$search}%");
            });
        }

        // Filter by priority
        if ($request->has('priority')) {
            $query->where('priority', $request->input('priority'));
        }

        // High priority filter
        if ($request->boolean('high_priority')) {
            $query->highPriority();
        }

        // Order by priority then created date
        $query->orderByPriority()->orderBy('created_at', 'desc');

        // Pagination
        $perPage = min($request->input('per_page', 15), 100);

        $pengumuman = $query->paginate($perPage);

        return PengumumanResource::collection($pengumuman);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): PengumumanResource|JsonResponse
    {
        $pengumuman = Pengumuman::active()->valid()->find($id);

        if (!$pengumuman) {
            return $this->notFound('Announcement not found');
        }

        return new PengumumanResource($pengumuman);
    }

    /**
     * Get high priority announcements.
     */
    public function highPriority(Request $request): AnonymousResourceCollection
    {
        $limit = min($request->input('limit', 5), 20);

        $pengumuman = Pengumuman::active()
            ->valid()
            ->highPriority()
            ->orderByPriority()
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        return PengumumanResource::collection($pengumuman);
    }
}
