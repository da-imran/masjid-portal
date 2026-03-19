<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RoleController extends Controller
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
     * Display a listing of roles.
     */
    public function index(Request $request): JsonResponse
    {
        if (!$this->hasPermission('roles.view')) {
            return response()->json([
                'message' => 'Anda tidak kebenaran untuk melihat peranan / You do not have permission to view roles',
            ], 403);
        }

        try {
            $query = Role::with('permissions');

            // Search
            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            // Filter is_active
            if ($request->has('is_active')) {
                $isActive = filter_var(
                    $request->input('is_active'),
                    FILTER_VALIDATE_BOOLEAN,
                    FILTER_NULL_ON_FAILURE
                );

                if ($isActive !== null) {
                    $query->where('is_active', $isActive);
                }
            }

            $perPage = min((int) $request->input('per_page', 10), 50);

            $roles = $query
                ->orderBy('id')
                ->paginate($perPage);

            return response()->json([
                'data' => $roles->items(),
                'meta' => [
                    'current_page' => $roles->currentPage(),
                    'last_page' => $roles->lastPage(),
                    'per_page' => $roles->perPage(),
                    'total' => $roles->total(),
                ],
            ], 200);

        } catch (\Exception $e) {
            Log::error('Failed to fetch roles', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Failed to fetch roles'
            ], 500);
        }
    }

    /**
     * Display the specified role.
     */
    public function show(int $id): JsonResponse
    {
        if (!$this->hasPermission('roles.view')) {
            return response()->json([
                'message' => 'Anda tidak kebenaran untuk melihat peranan ini / You do not have permission to view this role',
            ], 403);
        }

       try {
            $role = Role::with('permissions')->findOrFail($id);

            return response()->json([
                'data' => $role
            ], 200);

        } catch (ModelNotFoundException) {
            Log::error('Role update failed - not found', ['id' => $id]);
            return response()->json([
                'message' => 'Role not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Role update failed', ['id' => $id, 'error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Failed to update role: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created role in storage.
     */
    public function store(Request $request): JsonResponse
    {
        if (!$this->hasPermission('roles.create')) {
            return response()->json([
                'message' => 'Anda tidak kebenaran untuk mencipta peranan / You do not have permission to create roles',
            ], 403);
        }

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255|unique:roles,name',
                'description' => 'nullable|string',
                'is_active' => 'nullable',
            ]);

            $isActive = filter_var(
                $request->input('is_active', true),
                FILTER_VALIDATE_BOOLEAN,
                FILTER_NULL_ON_FAILURE
            );

            $role = Role::create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'is_active' => $isActive ?? true,
            ]);

            Log::info('Role created', ['id' => $role->id]);

            return response()->json([
                'message' => 'Role created successfully',
                'data' => $role,
            ], 201);

        } catch (\Exception $e) {
            Log::error('Role creation failed', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Failed to create role'
            ], 500);
        }
    }

    /**
     * Update the specified role in storage.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        if (!$this->hasPermission('roles.edit')) {
            Log::error('Role update failed - no permission', ['id' => $id]);
            return response()->json([
                'message' => 'Anda tidak kebenaran untuk edit peranan / You do not have permission to edit roles',
            ], 403);
        }

        try {
            $role = Role::findOrFail($id);
            Log::info('Role update request', [
                'id' => $id,
                'request_all' => $request->all(),
                'existing_role' => $role->toArray(),
            ]);

            if (in_array(strtolower($role->name), ['admin', 'super-admin'])) {
                return response()->json([
                    'message' => 'System role cannot be modified'
                ], 422);
            }

            $validated = $request->validate([
                'name' => "sometimes|string|max:255|unique:roles,name,{$id}",
                'description' => 'nullable|string',
                'is_active' => 'nullable',
            ]);

            Log::info('Role update validated', ['validated' => $validated]);

            // Handle is_active - if not provided, keep existing value
            $isActive = $role->is_active;
            if ($request->has('is_active')) {
                $inputValue = $request->input('is_active');
                Log::info('Role update is_active input', ['inputValue' => $inputValue, 'type' => gettype($inputValue)]);
                // Handle various input types: boolean, string "true"/"false", numeric
                if (is_bool($inputValue)) {
                    $isActive = $inputValue;
                } elseif (is_string($inputValue)) {
                    $isActive = in_array(strtolower($inputValue), ['true', '1', 'yes']);
                } elseif (is_numeric($inputValue)) {
                    $isActive = (int) $inputValue === 1;
                }
            }

            // Get description - if provided in request, use it (even if empty), otherwise keep existing
            $description = $role->description;
            if ($request->has('description')) {
                $description = $validated['description'] ?? '';
            }

            $updateData = [
                'name' => $validated['name'] ?? $role->name,
                'description' => $description,
                'is_active' => $isActive,
            ];

            Log::info('Role update data before save', ['updateData' => $updateData]);

            $role->update($updateData);

            Log::info('Role updated', ['id' => $role->id]);

            return response()->json([
                'message' => 'Role updated successfully',
                'data' => $role->fresh(),
            ], 200);

        } catch (ModelNotFoundException) {
            Log::error('Role update failed - not found', ['id' => $id]);
            return response()->json([
                'message' => 'Role not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Role update failed', ['id' => $id, 'error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Failed to update role: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified role from storage.
     */
    public function destroy(int $id): JsonResponse
    {
        if (!$this->hasPermission('roles.delete')) {
            return response()->json([
                'message' => 'Anda tidak kebenaran untuk memadam peranan / You do not have permission to delete roles',
            ], 403);
        }

       try {
            $role = Role::findOrFail($id);

            if (in_array(strtolower($role->name), ['admin', 'super-admin'])) {
                return response()->json([
                    'message' => 'System role cannot be deleted'
                ], 422);
            }

            if ($role->users()->exists()) {
                return response()->json([
                    'message' => 'Cannot delete role with assigned users'
                ], 422);
            }

            $role->delete();

            Log::info('Role deleted', ['id' => $id]);

            return response()->json([
                'message' => 'Role deleted successfully'
            ], 200);

        } catch (ModelNotFoundException) {
            Log::error('Role update failed - not found', ['id' => $id]);
            return response()->json([
                'message' => 'Role not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Role update failed', ['id' => $id, 'error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Failed to update role: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Add permission to a role.
     */
    public function addPermission(Request $request, int $roleId): JsonResponse
    {
        try {
            $role = Role::findOrFail($roleId);

            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'is_active' => 'nullable',
            ]);

            if ($role->permissions()->where('name', $validated['name'])->exists()) {
                return response()->json([
                    'message' => 'Permission already exists for this role'
                ], 422);
            }

            $isActive = filter_var(
                $request->input('is_active', true),
                FILTER_VALIDATE_BOOLEAN,
                FILTER_NULL_ON_FAILURE
            );

            $permission = $role->permissions()->create([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? $validated['name'],
                'is_active' => $isActive ?? true,
            ]);

            return response()->json([
                'message' => 'Permission added successfully',
                'data' => $permission,
            ], 201);

        } catch (ModelNotFoundException) {
            Log::error('Role update failed - not found', ['id' => $id]);
            return response()->json([
                'message' => 'Role not found'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Role update failed', ['id' => $id, 'error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Failed to update role: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update permission in a role.
     */
    public function updatePermission(Request $request, int $roleId, int $permissionId): JsonResponse
    {
        try {
            $role = Role::findOrFail($roleId);
            $permission = $role->permissions()->findOrFail($permissionId);

            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'description' => 'nullable|string',
                'is_active' => 'nullable',
            ]);

            $isActive = filter_var(
                $request->input('is_active', $permission->is_active),
                FILTER_VALIDATE_BOOLEAN,
                FILTER_NULL_ON_FAILURE
            );

            $permission->update([
                'name' => $validated['name'] ?? $permission->name,
                'description' => $validated['description'] ?? $permission->description,
                'is_active' => $isActive ?? $permission->is_active,
            ]);

            return response()->json([
                'message' => 'Permission updated successfully',
                'data' => $permission,
            ], 200);

        } catch (ModelNotFoundException) {
            return response()->json([
                'message' => 'Role or permission not found'
            ], 404);
        }
    }

    /**
     * Remove permission from a role.
     */
    public function removePermission(int $roleId, int $permissionId): JsonResponse
    {
       try {
            $role = Role::findOrFail($roleId);
            $permission = $role->permissions()->findOrFail($permissionId);

            $permission->delete();

            return response()->json([
                'message' => 'Permission removed successfully'
            ], 200);

        } catch (ModelNotFoundException) {
            return response()->json([
                'message' => 'Role or permission not found'
            ], 404);
        }
    }
}
