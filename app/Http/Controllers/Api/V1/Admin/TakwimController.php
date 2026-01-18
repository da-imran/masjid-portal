<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Takwim;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class TakwimController extends Controller
{
    /**
     * Display a listing of takwim.
     */
    public function index(Request $request): JsonResponse
    {
        $query = Takwim::with(['creator:id,name', 'updater:id,name']);

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

        // Default sort: event_date ASC (upcoming first), then created_at DESC
        $query->orderBy('event_date', 'asc')->orderBy('created_at', 'desc');

        // Pagination
        $perPage = $request->get('per_page', 15);
        $takwim = $query->paginate($perPage);

        return response()->json([
            'data' => $takwim->items(),
            'meta' => [
                'current_page' => $takwim->currentPage(),
                'last_page' => $takwim->lastPage(),
                'per_page' => $takwim->perPage(),
                'total' => $takwim->total(),
            ],
        ], 200);
    }

    /**
     * Store a newly created takwim.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = validator($request->all(), [
            'title_ms' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'description_ms' => 'nullable|string',
            'description_en' => 'nullable|string',
            'event_date' => 'required|date',
            'event_time' => 'nullable|date_format:H:i:s',
            'location_ms' => 'nullable|string|max:255',
            'location_en' => 'nullable|string|max:255',
            'image_name' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        $takwim = Takwim::create([
            'title_ms' => $request->title_ms,
            'title_en' => $request->title_en,
            'description_ms' => $request->description_ms,
            'description_en' => $request->description_en,
            'event_date' => $request->event_date,
            'event_time' => $request->event_time,
            'location_ms' => $request->location_ms,
            'location_en' => $request->location_en,
            'image_name' => $request->image_name,
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : true,
            'created_by' => auth()->id(),
        ]);

        // Load relationships
        $takwim->load(['creator:id,name', 'updater:id,name']);

        return response()->json([
            'message' => 'Takwim created successfully',
            'data' => $takwim,
        ], 201);
    }

    /**
     * Display the specified takwim.
     */
    public function show(int $id): JsonResponse
    {
        $takwim = Takwim::with(['creator:id,name', 'updater:id,name'])->findOrFail($id);

        return response()->json([
            'data' => $takwim,
        ], 200);
    }

    /**
     * Update the specified takwim.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $takwim = Takwim::findOrFail($id);

        $validator = validator($request->all(), [
            'title_ms' => 'sometimes|required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'description_ms' => 'nullable|string',
            'description_en' => 'nullable|string',
            'event_date' => 'nullable|date',
            'event_time' => 'nullable|date_format:H:i:s',
            'location_ms' => 'nullable|string|max:255',
            'location_en' => 'nullable|string|max:255',
            'image_name' => 'nullable|string|max:255',
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
        if ($request->has('event_date')) $updateData['event_date'] = $request->event_date;
        if ($request->has('event_time')) $updateData['event_time'] = $request->event_time;
        if ($request->has('location_ms')) $updateData['location_ms'] = $request->location_ms;
        if ($request->has('location_en')) $updateData['location_en'] = $request->location_en;
        if ($request->has('image_name')) $updateData['image_name'] = $request->image_name;
        if ($request->has('is_active')) $updateData['is_active'] = $request->boolean('is_active');

        $updateData['updated_by'] = auth()->id();

        $takwim->update($updateData);
        $takwim->load(['creator:id,name', 'updater:id,name']);

        return response()->json([
            'message' => 'Takwim updated successfully',
            'data' => $takwim,
        ], 200);
    }

    /**
     * Remove the specified takwim (soft delete - set is_deleted to true).
     */
    public function destroy(int $id): JsonResponse
    {
        $takwim = Takwim::findOrFail($id);
        $takwim->is_deleted = true;
        $takwim->save();

        return response()->json([
            'message' => 'Takwim deleted successfully',
            'data' => $takwim->load(['creator:id,name', 'updater:id,name']),
        ], 200);
    }
}
