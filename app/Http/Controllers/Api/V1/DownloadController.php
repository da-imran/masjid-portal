<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Downloads;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DownloadController extends Controller
{
    /**
     * Display a listing of downloads by category.
     */
    public function index(Request $request, string $category): JsonResponse
    {
        $validCategories = ['jadual_kuliah', 'nota_kuliah', 'borang'];

        if (!in_array($category, $validCategories)) {
            return response()->json([
                'error' => 'Invalid Category',
                'message' => 'Category must be one of: jadual_kuliah, nota_kuliah, borang',
            ], 400);
        }

        $query = Downloads::active()->category($category)->orderBy('created_at', 'desc');

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

        $downloads = $query->get();

        return response()->json($downloads);
    }

    /**
     * Display the specified download.
     */
    public function show(int $id): JsonResponse
    {
        $download = Downloads::active()->find($id);

        if (!$download) {
            return response()->json([
                'error' => 'Not Found',
                'message' => 'Download not found',
            ], 404);
        }

        // Increment download count
        $download->incrementDownloadCount();

        return response()->json($download);
    }

    /**
     * Get all downloads (optional, for admin purposes).
     */
    public function all(Request $request): JsonResponse
    {
        $query = Downloads::active()->orderBy('category')->orderBy('created_at', 'desc');

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

        // Filter by category
        if ($request->has('category')) {
            $category = $request->input('category');
            $query->category($category);
        }

        $downloads = $query->get();

        return response()->json($downloads);
    }
}
