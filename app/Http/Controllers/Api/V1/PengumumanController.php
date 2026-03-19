<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class PengumumanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show']);

        $this->middleware('permission:pengumuman.create')->only(['store']);
        $this->middleware('permission:pengumuman.edit')->only(['update']);
        $this->middleware('permission:pengumuman.delete')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Pengumuman::query();

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
            if ($request->has('is_high_priority')) {
                $isHighPriority = filter_var($request->input('is_high_priority'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                if ($isHighPriority !== null) {
                    $query->where('is_high_priority', $isHighPriority);
                }
            }

            // Filter by status
            if ($request->has('is_active')) {
                $isActive = filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                if ($isActive !== null) {
                    $query->where('is_active', $isActive);
                }
            }

            // Default sort
            $query->orderBy('created_at', 'desc');

            // Pagination
            $perPage = (int) $request->get('per_page', 15);
            $pengumuman = $query->paginate($perPage);

            return response()->json([
                'data' => $pengumuman->items(),
                'meta' => [
                    'current_page' => $pengumuman->currentPage(),
                    'last_page' => $pengumuman->lastPage(),
                    'per_page' => $pengumuman->perPage(),
                    'total' => $pengumuman->total(),
                ],
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error fetching Pengumuman list', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Failed to fetch Pengumuman',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = validator($request->all(), [
                'title_ms' => 'required|string|max:255',
                'title_en' => 'nullable|string|max:255',
                'description_ms' => 'nullable|string',
                'description_en' => 'nullable|string',
                'priority' => 'nullable|integer|between:1,5',
                'is_high_priority' => 'nullable|boolean',
                'is_active' => 'nullable|boolean',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
            ]);

            if ($validator->fails()) {
                Log::warning('Validation failed', $validator->errors()->toArray());
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $isActive = filter_var($request->input('is_active', true), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            $isHighPriority = filter_var($request->input('is_high_priority', false), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

            $pengumuman = Pengumuman::create([
                'title_ms' => $request->title_ms,
                'title_en' => $request->title_en,
                'description_ms' => $request->description_ms,
                'description_en' => $request->description_en,
                'priority' => $request->priority ?? 1,
                'is_high_priority' => $isHighPriority ?? false,
                'is_active' => $isActive ?? true,
                'start_date' => $request->start_date ?? now(),
                'end_date' => $request->end_date,
                'created_by' => auth()->id(),
            ]);

            Log::debug('Pengumuman created', ['id' => $pengumuman->id]);

            return response()->json([
                'message' => 'Pengumuman created successfully',
                'data' => $pengumuman,
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error creating Pengumuman', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Failed to create Pengumuman',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): JsonResponse
    {
        try {
            $pengumuman = Pengumuman::findOrFail($id);
            Log::debug('Pengumuman retrieved', ['id' => $pengumuman->id]);

            return response()->json([
                'data' => $pengumuman,
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning("Pengumuman with ID {$id} not found");
            return response()->json([
                'message' => 'Pengumuman not found',
            ], 404);

        } catch (\Exception $e) {
            Log::error('Error fetching Pengumuman', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Failed to fetch Pengumuman',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $pengumuman = Pengumuman::findOrFail($id);

            $validator = validator($request->all(), [
                'title_ms' => 'sometimes|required|string|max:255',
                'title_en' => 'nullable|string|max:255',
                'description_ms' => 'nullable|string',
                'description_en' => 'nullable|string',
                'priority' => 'nullable|integer|between:1,5',
                'is_high_priority' => 'nullable|boolean',
                'is_active' => 'nullable|boolean',
                'start_date' => 'nullable|date',
                'end_date' => 'nullable|date|after_or_equal:start_date',
            ]);

            if ($validator->fails()) {
                Log::warning('Validation failed', $validator->errors()->toArray());
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $updateData = [];

            if ($request->has('title_ms')) $updateData['title_ms'] = $request->title_ms;
            if ($request->has('title_en')) $updateData['title_en'] = $request->title_en;
            if ($request->has('description_ms')) $updateData['description_ms'] = $request->description_ms;
            if ($request->has('description_en')) $updateData['description_en'] = $request->description_en;
            if ($request->has('priority')) $updateData['priority'] = $request->priority;
            if ($request->has('is_high_priority')) {
                $updateData['is_high_priority'] = filter_var($request->input('is_high_priority'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            }
            if ($request->has('is_active')) {
                $updateData['is_active'] = filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            }
            if ($request->has('start_date')) $updateData['start_date'] = $request->start_date;
            if ($request->has('end_date')) $updateData['end_date'] = $request->end_date;

            $updateData['updated_by'] = auth()->id();

            $pengumuman->update($updateData);

            Log::debug('Pengumuman updated', ['id' => $pengumuman->id]);

            return response()->json([
                'message' => 'Pengumuman updated successfully',
                'data' => $pengumuman,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error updating Pengumuman', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Failed to update Pengumuman',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource.
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $pengumuman = Pengumuman::findOrFail($id);
            $pengumuman->delete();

            Log::debug('Pengumuman deleted', ['id' => $pengumuman->id]);

            return response()->json([
                'message' => 'Pengumuman deleted successfully',
                'data' => $pengumuman,
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning("Pengumuman with ID {$id} not found");

            return response()->json([
                'message' => 'Pengumuman not found',
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error deleting Pengumuman', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Failed to delete Pengumuman',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
