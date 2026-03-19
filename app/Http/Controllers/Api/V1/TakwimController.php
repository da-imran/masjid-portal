<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Takwim;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class TakwimController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show']);

        $this->middleware('permission:takwim.create')->only(['store']);
        $this->middleware('permission:takwim.edit')->only(['update']);
        $this->middleware('permission:takwim.delete')->only(['destroy']);
    }
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
            'event_time' => 'nullable|date_format:H:i',
            'location_ms' => 'nullable|string|max:255',
            'location_en' => 'nullable|string|max:255',
            'image_name' => 'nullable|string|max:255',
            'is_active' => 'nullable',
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        $isActive = filter_var($request->input('is_active', true), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        // Normalize event_time to H:i:s format if provided
        $eventTime = $request->event_time ? $request->event_time . ':00' : null;

        $takwim = Takwim::create([
            'title_ms' => $request->title_ms,
            'title_en' => $request->title_en,
            'description_ms' => $request->description_ms,
            'description_en' => $request->description_en,
            'event_date' => $request->event_date,
            'event_time' => $eventTime,
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
        try {
            $takwim = Takwim::with(['creator:id,name', 'updater:id,name'])->findOrFail($id);
            Log::debug('Takwim retrieved', ['id' => $kemudahan->id]);
            return response()->json([
                'data' => $takwim,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning("Takwim with ID {$id} not found");
            return response()->json([
                'message' => 'Takwim not found',
            ], 404);

        } catch (\Exception $e) {
            Log::error('Error fetching Takwim', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Failed to fetch Takwim',
                'error' => $e->getMessage()
            ], 500);
        }
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
            'event_time' => 'nullable|date_format:H:i',
            'location_ms' => 'nullable|string|max:255',
            'location_en' => 'nullable|string|max:255',
            'image_name' => 'nullable|string|max:255',
            'is_active' => 'nullable',
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        $isActive = filter_var($request->input('is_active', true), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        $updateData = [];

        // Use filled() to check for non-empty values
        if ($request->filled('title_ms')) $updateData['title_ms'] = $request->title_ms;
        if ($request->filled('title_en')) $updateData['title_en'] = $request->title_en;
        if ($request->filled('description_ms')) $updateData['description_ms'] = $request->description_ms;
        if ($request->filled('description_en')) $updateData['description_en'] = $request->description_en;
        if ($request->filled('event_date')) $updateData['event_date'] = $request->event_date;
        if ($request->filled('event_time')) $updateData['event_time'] = $request->event_time . ':00';
        if ($request->filled('location_ms')) $updateData['location_ms'] = $request->location_ms;
        if ($request->filled('location_en')) $updateData['location_en'] = $request->location_en;
        if ($request->filled('image_name')) $updateData['image_name'] = $request->image_name;
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
        try {
            $takwim = Takwim::findOrFail($id);
            $takwim->delete();

            Log::debug('Takwim deleted', ['id' => $takwim->id]);

            return response()->json([
                'message' => 'Takwim deleted successfully',
                'data' => $takwim->load(['creator:id,name', 'updater:id,name']),
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning("Takwim with ID: {$id} not found");

            return response()->json([
                'message' => 'Takwim not found',
            ], 404);
        }
    }
}
