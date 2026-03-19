<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\BeritaSemasa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class BeritaSemasaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show']);

        $this->middleware('permission:berita.create')->only(['store']);
        $this->middleware('permission:berita.edit')->only(['update']);
        $this->middleware('permission:berita.delete')->only(['destroy']);
    }
    /**
     * Display a listing of berita semasa.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = BeritaSemasa::with(['creator:id,name', 'updater:id,name']);

            // Filter by status (convert string to boolean safely)
            if ($request->has('is_active')) {
                $isActive = filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                if ($isActive !== null) {
                    $query->where('is_active', $isActive);
                }
            }

            if ($request->has('is_featured')) {
                $isFeatured = filter_var($request->input('is_featured'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                if ($isFeatured !== null) {
                    $query->where('is_featured', $isFeatured);
                }
            }

            // Search by title
            if ($request->has('search')) {
                $search = $request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->where('title_ms', 'like', "%{$search}%")
                    ->orWhere('title_en', 'like', "%{$search}%");
                });
            }

            // Default sort
            $query->orderBy('created_at', 'desc');

            // Pagination
            $perPage = (int) $request->get('per_page', 15);
            $berita = $query->paginate($perPage);

            return response()->json([
                'data' => $berita->items(),
                'meta' => [
                    'current_page' => $berita->currentPage(),
                    'last_page' => $berita->lastPage(),
                    'per_page' => $berita->perPage(),
                    'total' => $berita->total(),
                ],
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error fetching BeritaSemasa list', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Failed to fetch Berita Semasa',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created berita semasa.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validator = validator($request->all(), [
                'title_ms' => 'required|string|max:255',
                'title_en' => 'nullable|string|max:255',
                'description_ms' => 'nullable|string',
                'description_en' => 'nullable|string',
                'content_ms' => 'required|string',
                'content_en' => 'nullable|string',
                'image' => 'nullable|image|max:5120', // max 5MB
                'is_active' => 'nullable',
                'is_featured' => 'nullable',
                'published_at' => 'nullable|date',
            ]);

            if ($validator->fails()) {
                Log::warning('Validation failed', $validator->errors()->toArray());
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $isActive = filter_var($request->input('is_active', true), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            $isFeatured = filter_var($request->input('is_featured', false), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

            $imageName = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                Storage::disk('public')->putFileAs('berita', $image, $imageName);
                Log::debug('Image stored', ['image_name' => $imageName]);
            }

            $berita = BeritaSemasa::create([
                'title_ms' => $request->title_ms,
                'title_en' => $request->title_en,
                'description_ms' => $request->description_ms,
                'description_en' => $request->description_en,
                'content_ms' => $request->content_ms,
                'content_en' => $request->content_en,
                'image_name' => $imageName,
                'is_active' => $isActive ?? false,
                'is_featured' => $isFeatured ?? false,
                'published_at' => $request->published_at ?? now(),
                'created_by' => auth()->id(),
            ]);

            Log::debug('Berita Semasa created', ['id' => $berita->id]);

            $berita->load(['creator:id,name', 'updater:id,name']);

            return response()->json([
                'message' => 'Berita Semasa created successfully',
                'data' => $berita,
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error creating Berita Semasa', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Failed to create Berita Semasa',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified berita semasa.
     */
    public function show(int $id): JsonResponse
    {
        try {
            $berita = BeritaSemasa::with(['creator:id,name', 'updater:id,name'])->findOrFail($id);
            Log::debug('Berita Semasa retrieved', ['id' => $berita->id]);

            return response()->json([
                'data' => $berita,
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning("BeritaSemasa with ID {$id} not found");
            return response()->json([
                'message' => 'Berita Semasa not found',
            ], 404);

        } catch (\Exception $e) {
            Log::error('Error fetching BeritaSemasa', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Failed to fetch Berita Semasa',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified berita semasa.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $berita = BeritaSemasa::findOrFail($id);
            $validator = validator($request->all(), [
                'title_ms' => 'sometimes|required|string|max:255',
                'title_en' => 'nullable|string|max:255',
                'description_ms' => 'nullable|string',
                'description_en' => 'nullable|string',
                'content_ms' => 'sometimes|required|string',
                'content_en' => 'nullable|string',
                'image' => 'nullable|image|max:5120', // Image upload, max 5MB
                'is_active' => 'nullable',
                'is_featured' => 'nullable',
                'published_at' => 'nullable|date',
            ]);

            if ($validator->fails()) {
                Log::warning('Validation failed', $validator->errors()->toArray());
                return response()->json([
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $isActive = filter_var($request->input('is_active', true), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            $isFeatured = filter_var($request->input('is_featured', false), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

            $updateData = [];

            if ($request->has('title_ms')) $updateData['title_ms'] = $request->title_ms;
            if ($request->has('title_en')) $updateData['title_en'] = $request->title_en;
            if ($request->has('description_ms')) $updateData['description_ms'] = $request->description_ms;
            if ($request->has('description_en')) $updateData['description_en'] = $request->description_en;
            if ($request->has('content_ms')) $updateData['content_ms'] = $request->content_ms;
            if ($request->has('content_en')) $updateData['content_en'] = $request->content_en;
            if ($request->has('is_active')) $updateData['is_active'] = $isActive;
            if ($request->has('is_featured')) $updateData['is_featured'] = $isFeatured;
            if ($request->has('published_at')) $updateData['published_at'] = $request->published_at;


            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($berita->image_name && Storage::disk('public')->exists('berita/' . $berita->image_name)) {
                    Storage::disk('public')->delete('berita/' . $berita->image_name);
                }

                $image = $request->file('image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                Storage::disk('public')->putFileAs('berita', $image, $imageName);
                $updateData['image_name'] = $imageName;
            }

            $updateData['updated_by'] = auth()->id();

            $berita->update($updateData);
            $berita->load(['creator:id,name', 'updater:id,name']);

            Log::debug('Berita Semasa updated', ['id' => $berita->id]);

            return response()->json([
                'message' => 'Berita Semasa updated successfully',
                'data' => $berita,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error updating Berita Semasa', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Failed to update Berita Semasa',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified berita semasa (hard-delete).
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $berita = BeritaSemasa::findOrFail($id);
            $berita->delete();

            Log::debug('Berita Semasa deleted', ['id' => $berita->id]);

            return response()->json([
                'message' => 'Berita Semasa deleted successfully',
                'data' => $berita->load(['creator:id,name', 'updater:id,name']),
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning("Berita Semasa with ID: {$id} not found");

            return response()->json([
                'message' => 'Berita Semasa not found',
            ], 404);
        }
    }
}
