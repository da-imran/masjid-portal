<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Kemudahan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class KemudahanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show']);

        $this->middleware('permission:kemudahan.create')->only(['store']);
        $this->middleware('permission:kemudahan.edit')->only(['update']);
        $this->middleware('permission:kemudahan.delete')->only(['destroy']);
    }
    /**
     * Display a listing of kemudahan.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Kemudahan::with(['creator:id,name', 'updater:id,name']);

            // Filter by status
            if ($request->has('is_active')) {
                $query->where('is_active', $request->boolean('is_active'));
            }

            // Search by title
            if ($request->has('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('title_ms', 'like', "%{$search}%")
                        ->orWhere('title_en', 'like', "%{$search}%");
                });
            }

            // Default sort: order_column ASC, then created_at DESC
            $query->orderBy('order_column', 'asc')->orderBy('created_at', 'desc');

            // Pagination
            $perPage = $request->get('per_page', 15);
            $kemudahan = $query->paginate($perPage);

            return response()->json([
                'data' => $kemudahan->items(),
                'meta' => [
                    'current_page' => $kemudahan->currentPage(),
                    'last_page' => $kemudahan->lastPage(),
                    'per_page' => $kemudahan->perPage(),
                    'total' => $kemudahan->total(),
                ],
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error fetching Kemudahan list', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Failed to fetch Kemudahan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created kemudahan.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = validator($request->all(), [
                'title_ms' => 'required|string|max:255',
                'title_en' => 'nullable|string|max:255',
                'description_ms' => 'nullable|string',
                'description_en' => 'nullable|string',
                'image' => 'nullable|image|max:5120',
                'order_column' => 'nullable|integer|min:0',
                'is_active' => 'nullable',
            ]);

            if ($validator->fails()) {
                Log::warning('Validation failed', $validator->errors()->toArray());
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }
            $isActive = filter_var($request->input('is_active', true), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

            $imageName = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                Storage::disk('public')->putFileAs('kemudahan', $image, $imageName);
            }

            $kemudahan = Kemudahan::create([
                'title_ms' => $request->title_ms,
                'title_en' => $request->title_en,
                'description_ms' => $request->description_ms,
                'description_en' => $request->description_en,
                'image_name' => $imageName,
                'order_column' => $request->order_column ?? 0,
                'is_active' => $isActive ?? false,
                'created_by' => auth()->id(),
            ]);

            // Load relationships
            $kemudahan->load(['creator:id,name', 'updater:id,name']);

            return response()->json([
                'message' => 'Kemudahan created successfully',
                'data' => $kemudahan,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating Kemudahan', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Failed to create Kemudahan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified kemudahan.
     */
    public function show(int $id): JsonResponse
    {
        try {
            $kemudahan = Kemudahan::with(['creator:id,name', 'updater:id,name'])->findOrFail($id);
            Log::debug('Kemudahan retrieved', ['id' => $kemudahan->id]);
            return response()->json([
                'data' => $kemudahan,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning("Kemudahan with ID {$id} not found");
            return response()->json([
                'message' => 'Kemudahan not found',
            ], 404);

        } catch (\Exception $e) {
            Log::error('Error fetching Kemudahan', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Failed to fetch Kemudahan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified kemudahan.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $kemudahan = Kemudahan::findOrFail($id);
            $validator = validator($request->all(), [
                'title_ms' => 'sometimes|required|string|max:255',
                'title_en' => 'nullable|string|max:255',
                'description_ms' => 'nullable|string',
                'description_en' => 'nullable|string',
                'image' => 'nullable|image|max:5120',
                'order_column' => 'nullable|integer|min:0',
                'is_active' => 'nullable',
            ]);

            if ($validator->fails()) {
                Log::warning('Validation failed', $validator->errors()->toArray());
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $isActive = filter_var($request->input('is_active', true), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

            $updateData = [];

            // Use filled() to check for non-empty values
            if ($request->filled('title_ms')) $updateData['title_ms'] = $request->title_ms;
            if ($request->filled('title_en')) $updateData['title_en'] = $request->title_en;
            if ($request->filled('description_ms')) $updateData['description_ms'] = $request->description_ms;
            if ($request->filled('description_en')) $updateData['description_en'] = $request->description_en;
            if ($request->filled('order_column')) $updateData['order_column'] = $request->order_column;
            if ($request->has('is_active')) $updateData['is_active'] = $isActive;

            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($kemudahan->image_name && Storage::disk('public')->exists('kemudahan/' . $kemudahan->image_name)) {
                    Storage::disk('public')->delete('kemudahan/' . $kemudahan->image_name);
                }

                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                Storage::disk('public')->putFileAs('kemudahan', $image, $imageName);
                $updateData['image_name'] = $imageName;
            }

            $updateData['updated_by'] = auth()->id();

            $kemudahan->update($updateData);
            $kemudahan->load(['creator:id,name', 'updater:id,name']);

            return response()->json([
                'message' => 'Kemudahan updated successfully',
                'data' => $kemudahan,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error updating Kemudahan', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Failed to update Kemudahan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified kemudahan (hard-delete).
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $kemudahan = Kemudahan::findOrFail($id);
            $kemudahan->delete();

            Log::debug('Kemudahan deleted', ['id' => $kemudahan->id]);

            return response()->json([
                'message' => 'Kemudahan deleted successfully',
                'data' => $kemudahan->load(['creator:id,name', 'updater:id,name']),
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning("Kemudahan with ID: {$id} not found");

            return response()->json([
                'message' => 'Kemudahan not found',
            ], 404);
        }
    }
}
