<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Takwim;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TakwimController extends Controller
{
    /**
     * Display a listing of takwim.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Takwim::active()->orderBy('event_date', 'asc');

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

        $takwim = $query->get();

        return response()->json($takwim);
    }

    /**
     * Display upcoming takwim.
     */
    public function upcoming(Request $request): JsonResponse
    {
        $limit = min($request->input('limit', 10), 50);

        $takwim = Takwim::active()
            ->upcoming()
            ->limit($limit)
            ->get();

        return response()->json($takwim);
    }

    /**
     * Display the specified takwim.
     */
    public function show(int $id): JsonResponse
    {
        $takwim = Takwim::active()->find($id);

        if (!$takwim) {
            return response()->json([
                'error' => 'Not Found',
                'message' => 'Takwim not found',
            ], 404);
        }

        return response()->json($takwim);
    }
}
