<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\BeritaSemasaResource;
use App\Models\BeritaSemasa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BeritaSemasaController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = BeritaSemasa::active()->published();

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

        // Filter by featured
        if ($request->boolean('featured')) {
            $query->featured();
        }

        // Order by
        $orderBy = $request->input('order_by', 'published_at');
        $orderDir = $request->input('order_dir', 'desc');
        $query->orderBy($orderBy, $orderDir);

        // Pagination
        $perPage = min($request->input('per_page', 15), 100);

        $berita = $query->paginate($perPage);

        return BeritaSemasaResource::collection($berita);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): BeritaSemasaResource|JsonResponse
    {
        $berita = BeritaSemasa::active()->published()->find($id);

        if (!$berita) {
            return $this->notFound('News not found');
        }

        return new BeritaSemasaResource($berita);
    }

    /**
     * Increment view count for the specified news.
     */
    public function incrementView(int $id): JsonResponse
    {
        $berita = BeritaSemasa::active()->published()->find($id);

        if (!$berita) {
            return $this->notFound('News not found');
        }

        $viewCount = $berita->incrementViewCount();

        return $this->success([
            'view_count' => $viewCount,
        ], 'View count incremented');
    }

    /**
     * Get featured news.
     */
    public function featured(Request $request): AnonymousResourceCollection
    {
        $limit = min($request->input('limit', 5), 20);

        $berita = BeritaSemasa::active()
            ->featured()
            ->published()
            ->orderBy('published_at', 'desc')
            ->limit($limit)
            ->get();

        return BeritaSemasaResource::collection($berita);
    }
}
