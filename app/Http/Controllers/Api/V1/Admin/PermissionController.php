<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Throwable;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
    }

    /**
     * Check if user has permission
     */
    private function hasPermission(string $permission): bool
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }

        // Load role with permissions if not already loaded
        $user->load(['role', 'role.permissions']);

        // Admin role has all permissions
        if ($user->role && $user->role->name === 'Admin') {
            return true;
        }

        // Check specific permission
        if ($user->role && $user->role->permissions) {
            return $user->role->permissions->contains('name', $permission);
        }

        return false;
    }

    /**
     * Display a listing of permissions.
     */
    public function index(Request $request): JsonResponse
    {
        if (!$this->hasPermission('permissions.view')) {
            return response()->json([
                'message' => 'Anda tidak kebenaran untuk melihat kebenaran / You do not have permission to view permissions',
            ], 403);
        }

        try {
            $query = Permission::with('role');

            if ($request->filled('role_id')) {
                $query->where('role_id', $request->role_id);
            }

            if ($request->has('is_active')) {
                $query->where('is_active', $request->boolean('is_active'));
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // Safe sorting
            $allowedSorts = ['id', 'name', 'created_at'];
            $sortBy = in_array($request->get('sort_by'), $allowedSorts)
                ? $request->get('sort_by')
                : 'created_at';

            $sortOrder = $request->get('sort_order') === 'asc' ? 'asc' : 'desc';

            $permissions = $query
                ->orderBy($sortBy, $sortOrder)
                ->paginate($request->get('per_page', 15));

            return response()->json([
                'data' => $permissions->items(),
                'meta' => [
                    'current_page' => $permissions->currentPage(),
                    'last_page' => $permissions->lastPage(),
                    'per_page' => $permissions->perPage(),
                    'total' => $permissions->total(),
                ],
            ], 200);
        } catch (Throwable $e) {
            Log::error('Permission index failed', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => 'Failed to retrieve permissions',
            ], 500);
        }
    }

    /**
     * Store a newly created permission.
     */
    public function store(Request $request): JsonResponse
    {
        if (!$this->hasPermission('permissions.create')) {
            return response()->json([
                'message' => 'Anda tidak kebenaran untuk mencipta kebenaran / You do not have permission to create permissions',
            ], 403);
        }

        try {
            $validated = $request->validate([
                'role_id' => 'required|exists:roles,id',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'is_active' => 'nullable',
            ]);

            $permission = Permission::create([
                'role_id' => $validated['role_id'],
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'is_active' => $request->has('is_active')
                    ? filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN)
                    : true,
            ]);

            return response()->json([
                'message' => 'Permission created successfully',
                'data' => $permission->load('role'),
            ], 201);
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            Log::error('Permission store failed', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => 'Failed to create permission',
            ], 500);
        }
    }

    /**
     * Display the specified permission.
     */
    public function show(int $id): JsonResponse
    {
        if (!$this->hasPermission('permissions.view')) {
            return response()->json([
                'message' => 'Anda tidak kebenaran untuk melihat kebenaran ini / You do not have permission to view this permission',
            ], 403);
        }

        try {
            $permission = Permission::with('role')->findOrFail($id);

            return response()->json([
                'data' => $permission,
            ], 200);
        } catch (Throwable $e) {
            Log::error('Permission show failed', ['id' => $id]);

            return response()->json([
                'message' => 'Permission not found',
            ], 404);
        }
    }

    /**
     * Update the specified permission.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        if (!$this->hasPermission('permissions.edit')) {
            return response()->json([
                'message' => 'Anda tidak kebenaran untuk edit kebenaran / You do not have permission to edit permissions',
            ], 403);
        }

        try {
            $permission = Permission::findOrFail($id);

            $validated = $request->validate([
                'role_id' => 'sometimes|exists:roles,id',
                'name' => 'sometimes|string|max:255',
                'description' => 'nullable|string',
                'is_active' => 'nullable',
            ]);

            $permission->update([
                'role_id' => $validated['role_id'] ?? $permission->role_id,
                'name' => $validated['name'] ?? $permission->name,
                'description' => $validated['description'] ?? $permission->description,
                'is_active' => $request->has('is_active')
                    ? filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN)
                    : $permission->is_active,
            ]);

            return response()->json([
                'message' => 'Permission updated successfully',
                'data' => $permission->load('role'),
            ], 200);
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            Log::error('Permission update failed', ['id' => $id]);

            return response()->json([
                'message' => 'Failed to update permission',
            ], 500);
        }
    }

    /**
     * Remove the specified permission.
     */
    public function destroy(int $id): JsonResponse
    {
        if (!$this->hasPermission('permissions.delete')) {
            return response()->json([
                'message' => 'Anda tidak kebenaran untuk memadam kebenaran / You do not have permission to delete permissions',
            ], 403);
        }

        try {
            $permission = Permission::findOrFail($id);
            $permission->delete();

            return response()->json([
                'message' => 'Permission deleted successfully',
            ], 200);
        } catch (Throwable $e) {
            Log::error('Permission delete failed', ['id' => $id, 'error' => $e->getMessage()]);

            return response()->json([
                'message' => 'Failed to delete permission: ' . $e->getMessage(),
            ], 500);
        }
    }
}
