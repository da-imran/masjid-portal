<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kemudahan;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class KemudahanController extends Controller
{
    /**
     * Display a listing of kemudahan.
     */
    public function index(Request $request): JsonResponse
    {
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
    }

    /**
     * Store a newly created kemudahan.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = validator($request->all(), [
            'title_ms' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'description_ms' => 'nullable|string',
            'description_en' => 'nullable|string',
            'icon_name' => 'nullable|string|max:255',
            'image_name' => 'nullable|string|max:255',
            'order_column' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        $kemudahan = Kemudahan::create([
            'title_ms' => $request->title_ms,
            'title_en' => $request->title_en,
            'description_ms' => $request->description_ms,
            'description_en' => $request->description_en,
            'icon_name' => $request->icon_name,
            'image_name' => $request->image_name,
            'order_column' => $request->order_column ?? 0,
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : true,
            'created_by' => auth()->id(),
        ]);

        // Load relationships
        $kemudahan->load(['creator:id,name', 'updater:id,name']);

        return response()->json([
            'message' => 'Kemudahan created successfully',
            'data' => $kemudahan,
        ], 201);
    }

    /**
     * Display the specified kemudahan.
     */
    public function show(int $id): JsonResponse
    {
        $kemudahan = Kemudahan::with(['creator:id,name', 'updater:id,name'])->findOrFail($id);

        return response()->json([
            'data' => $kemudahan,
        ], 200);
    }

    /**
     * Update the specified kemudahan.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $kemudahan = Kemudahan::findOrFail($id);

        $validator = validator($request->all(), [
            'title_ms' => 'sometimes|required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'description_ms' => 'nullable|string',
            'description_en' => 'nullable|string',
            'icon_name' => 'nullable|string|max:255',
            'image_name' => 'nullable|string|max:255',
            'order_column' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        $updateData = [];

        if ($request->has('title_ms')) $updateData['title_ms'] = $request->title_ms;
        if ($request->has('title_en')) $updateData['title_en'] = $request->title_en;
        if ($request->has('description_ms')) $updateData['description_ms'] = $request->description_ms;
        if ($request->has('description_en')) $updateData['description_en'] = $request->description_en;
        if ($request->has('icon_name')) $updateData['icon_name'] = $request->icon_name;
        if ($request->has('image_name')) $updateData['image_name'] = $request->image_name;
        if ($request->has('order_column')) $updateData['order_column'] = $request->order_column;
        if ($request->has('is_active')) $updateData['is_active'] = $request->boolean('is_active');

        $updateData['updated_by'] = auth()->id();

        $kemudahan->update($updateData);
        $kemudahan->load(['creator:id,name', 'updater:id,name']);

        return response()->json([
            'message' => 'Kemudahan updated successfully',
            'data' => $kemudahan,
        ], 200);
    }

    /**
     * Remove the specified kemudahan.
     */
    public function destroy(int $id): JsonResponse
    {
        $kemudahan = Kemudahan::findOrFail($id);
        $kemudahan->delete();

        return response()->json([
            'message' => 'Kemudahan deleted successfully',
        ], 200);
    }
}
