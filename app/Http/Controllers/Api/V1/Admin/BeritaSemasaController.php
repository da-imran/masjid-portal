<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\BeritaSemasa;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class BeritaSemasaController extends Controller
{
    /**
     * Display a listing of berita semasa.
     */
    public function index(Request $request): JsonResponse
    {
        $query = BeritaSemasa::with(['creator:id,name', 'updater:id,name']);

        // Filter by status
        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        // Filter by featured
        if ($request->has('is_featured')) {
            $query->where('is_featured', $request->boolean('is_featured'));
        }

        // Search by title
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title_ms', 'like', "%{$search}%")
                    ->orWhere('title_en', 'like', "%{$search}%");
            });
        }

        // Default sort: created_at DESC (newest first)
        $query->orderBy('created_at', 'desc');

        // Pagination
        $perPage = $request->get('per_page', 15);
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
    }

    /**
     * Store a newly created berita semasa.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = validator($request->all(), [
            'title_ms' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'description_ms' => 'nullable|string',
            'description_en' => 'nullable|string',
            'content_ms' => 'required|string',
            'content_en' => 'nullable|string',
            'image' => 'nullable|image|max:5120', // Image upload, max 5MB
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        $imageName = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/berita', $imageName);
        }

        $berita = BeritaSemasa::create([
            'title_ms' => $request->title_ms,
            'title_en' => $request->title_en,
            'description_ms' => $request->description_ms,
            'description_en' => $request->description_en,
            'content_ms' => $request->content_ms,
            'content_en' => $request->content_en,
            'image_name' => $imageName,
            'is_active' => $request->has('is_active') ? $request->boolean('is_active') : true,
            'is_featured' => $request->has('is_featured') ? $request->boolean('is_featured') : false,
            'published_at' => $request->published_at ?? now(),
            'created_by' => auth()->id(),
        ]);

        // Load relationships
        $berita->load(['creator:id,name', 'updater:id,name']);

        return response()->json([
            'message' => 'Berita Semasa created successfully',
            'data' => $berita,
        ], 201);
    }

    /**
     * Display the specified berita semasa.
     */
    public function show(int $id): JsonResponse
    {
        $berita = BeritaSemasa::with(['creator:id,name', 'updater:id,name'])->findOrFail($id);

        return response()->json([
            'data' => $berita,
        ], 200);
    }

    /**
     * Update the specified berita semasa.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $berita = BeritaSemasa::findOrFail($id);

        $validator = validator($request->all(), [
            'title_ms' => 'sometimes|required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'description_ms' => 'nullable|string',
            'description_en' => 'nullable|string',
            'content_ms' => 'sometimes|required|string',
            'content_en' => 'nullable|string',
            'image' => 'nullable|image|max:5120', // Image upload, max 5MB
            'is_active' => 'nullable|boolean',
            'is_featured' => 'nullable|boolean',
            'published_at' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        $updateData = [];

        if ($request->has('title_ms')) $updateData['title_ms'] = $request->title_ms;
        if ($request->has('title_en')) $updateData['title_en'] = $request->title_en;
        if ($request->has('description_ms')) $updateData['description_ms'] = $request->description_ms;
        if ($request->has('description_en')) $updateData['description_en'] = $request->description_en;
        if ($request->has('content_ms')) $updateData['content_ms'] = $request->content_ms;
        if ($request->has('content_en')) $updateData['content_en'] = $request->content_en;
        if ($request->has('is_active')) $updateData['is_active'] = $request->boolean('is_active');
        if ($request->has('is_featured')) $updateData['is_featured'] = $request->boolean('is_featured');
        if ($request->has('published_at')) $updateData['published_at'] = $request->published_at;

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($berita->image_name && Storage::exists('public/berita/' . $berita->image_name)) {
                Storage::delete('public/berita/' . $berita->image_name);
            }

            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/berita', $imageName);
            $updateData['image_name'] = $imageName;
        }

        $updateData['updated_by'] = auth()->id();

        $berita->update($updateData);
        $berita->load(['creator:id,name', 'updater:id,name']);

        return response()->json([
            'message' => 'Berita Semasa updated successfully',
            'data' => $berita,
        ], 200);
    }

    /**
     * Remove the specified berita semasa (soft delete - set is_deleted to true).
     */
    public function destroy(int $id): JsonResponse
    {
        $berita = BeritaSemasa::findOrFail($id);
        $berita->is_deleted = true;
        $berita->save();

        return response()->json([
            'message' => 'Berita Semasa deleted successfully',
            'data' => $berita->load(['creator:id,name', 'updater:id,name']),
        ], 200);
    }
}
