<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Kemudahan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class KemudahanController extends Controller
{
    /**
     * Display a listing of kemudahan.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Kemudahan::active()->ordered();

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

        $kemudahan = $query->get();

        return response()->json($kemudahan);
    }

    /**
     * Display the specified kemudahan.
     */
    public function show(int $id): JsonResponse
    {
        $kemudahan = Kemudahan::active()->find($id);

        if (!$kemudahan) {
            return response()->json([
                'error' => 'Not Found',
                'message' => 'Kemudahan not found',
            ], 404);
        }

        return response()->json($kemudahan);
    }
}
