<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Throwable;

class UserController extends Controller
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
     * Display a listing of users.
     */
    public function index(Request $request): JsonResponse
    {
        if (!$this->hasPermission('users.view')) {
            return response()->json([
                'message' => 'Anda tidak kebenaran untuk mengakses halaman ini / You do not have permission to access this page',
            ], 403);
        }

        try {
            $query = User::with('role');

            if ($request->filled('role_id')) {
                $query->where('role_id', $request->role_id);
            }

            if ($request->has('is_blocked')) {
                $query->where('is_blocked', $request->boolean('is_blocked'));
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            // Safe sorting
            $allowedSorts = ['id', 'name', 'email', 'created_at'];
            $sortBy = in_array($request->get('sort_by'), $allowedSorts)
                ? $request->get('sort_by')
                : 'created_at';

            $sortOrder = $request->get('sort_order') === 'asc' ? 'asc' : 'desc';

            $users = $query
                ->orderBy($sortBy, $sortOrder)
                ->paginate($request->get('per_page', 15));

            return response()->json([
                'data' => $users->items(),
                'meta' => [
                    'current_page' => $users->currentPage(),
                    'last_page' => $users->lastPage(),
                    'per_page' => $users->perPage(),
                    'total' => $users->total(),
                ],
            ], 200);
        } catch (Throwable $e) {
            Log::error('User index failed', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => 'Failed to retrieve users',
            ], 500);
        }
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request): JsonResponse
    {
        if (!$this->hasPermission('users.create')) {
            return response()->json([
                'message' => 'Anda tidak kebenaran untuk mencipta pengguna / You do not have permission to create users',
            ], 403);
        }

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
                'password_confirmation' => 'required|string|min:8',
                'role_id' => 'nullable|exists:roles,id',
            ], [
                'email.unique' => 'E-mel ini telah digunakan. Sila gunakan e-mel yang lain.',
                'email.email' => 'Sila masukkan e-mel yang sah.',
                'password.min' => 'Kata laluan mestilah sekurang-kurangnya 8 aksara.',
                'password.confirmed' => 'Pengesahan kata laluan tidak sepadan / Password confirmation does not match.',
                'password_confirmation.required' => 'Sahkan kata laluan diperlukan / Password confirmation is required.',
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role_id' => $validated['role_id'] ?? null,
                'is_active' => true,
            ]);

            return response()->json([
                'message' => 'User created successfully',
                'data' => $user->load('role'),
            ], 201);
        } catch (ValidationException $e) {
            // Return validation errors in a format react-admin expects
            $errors = [];
            foreach ($e->errors() as $field => $messages) {
                $errors[$field] = is_array($messages) ? $messages : [$messages];
            }

            return response()->json([
                'message' => 'Validation failed',
                'errors' => $errors,
            ], 422);
        } catch (Throwable $e) {
            Log::error('User store failed', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => 'Failed to create user',
            ], 500);
        }
    }

    /**
     * Display the specified user.
     */
    public function show(int $id): JsonResponse
    {
        if (!$this->hasPermission('users.view')) {
            return response()->json([
                'message' => 'Anda tidak kebenaran untuk melihat pengguna ini / You do not have permission to view this user',
            ], 403);
        }

       try {
            $user = User::with('role')->findOrFail($id);

            return response()->json([
                'data' => $user,
            ], 200);
        } catch (Throwable $e) {
            Log::error('User show failed', ['id' => $id]);

            return response()->json([
                'message' => 'User not found',
            ], 404);
        }
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        if (!$this->hasPermission('users.edit')) {
            return response()->json([
                'message' => 'Anda tidak kebenaran untuk edit pengguna / You do not have permission to edit users',
            ], 403);
        }

        try {
            $user = User::findOrFail($id);

            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'email' => [
                    'sometimes',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique('users', 'email')->ignore($user->id),
                ],
                'password' => 'nullable|string|min:8|confirmed',
                'password_confirmation' => 'nullable|string|min:8',
                'role_id' => 'nullable|exists:roles,id',
                'is_active' => 'nullable',
                'is_blocked' => 'nullable',
                'blocked_reason' => 'nullable|string|max:500',
            ], [
                'email.unique' => 'E-mel ini telah digunakan. Sila gunakan e-mel yang lain.',
                'email.email' => 'Sila masukkan e-mel yang sah.',
                'password.min' => 'Kata laluan mestilah sekurang-kurangnya 8 aksara.',
                'password.confirmed' => 'Pengesahan kata laluan tidak sepadan / Password confirmation does not match.',
            ]);

            $updateData = [
                'name' => $validated['name'] ?? $user->name,
                'email' => $validated['email'] ?? $user->email,
            ];

            if (!empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            if (array_key_exists('role_id', $validated)) {
                $updateData['role_id'] = $validated['role_id'];
            }

            if ($request->has('is_active')) {
                $updateData['is_active'] = filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN);
            }

            if ($request->has('is_blocked')) {
                $isBlocked = filter_var($request->is_blocked, FILTER_VALIDATE_BOOLEAN);
                $updateData['is_blocked'] = $isBlocked;
                $updateData['blocked_at'] = $isBlocked ? now() : null;
                $updateData['blocked_reason'] = $isBlocked
                    ? ($validated['blocked_reason'] ?? null)
                    : null;
            }

            $user->update($updateData);

            return response()->json([
                'message' => 'User updated successfully',
                'data' => $user->load('role'),
            ], 200);
        } catch (ValidationException $e) {
            // Return validation errors in a format react-admin expects
            $errors = [];
            foreach ($e->errors() as $field => $messages) {
                $errors[$field] = is_array($messages) ? $messages : [$messages];
            }

            return response()->json([
                'message' => 'Validation failed',
                'errors' => $errors,
            ], 422);
        } catch (Throwable $e) {
            Log::error('User update failed', ['id' => $id]);

            return response()->json([
                'message' => 'Failed to update user',
            ], 500);
        }
    }

    /**
     * Remove the specified user.
     */
    public function destroy(int $id): JsonResponse
    {
        if (!$this->hasPermission('users.delete')) {
            return response()->json([
                'message' => 'Anda tidak kebenaran untuk memadam pengguna / You do not have permission to delete users',
            ], 403);
        }

        try {
            if ($id === auth()->id()) {
                return response()->json([
                    'message' => 'You cannot delete your own account',
                ], 403);
            }

            $user = User::findOrFail($id);
            $user->delete();

            return response()->json([
                'message' => 'User deleted successfully',
            ], 200);
        } catch (Throwable $e) {
            Log::error('User delete failed', ['id' => $id]);

            return response()->json([
                'message' => 'Failed to delete user',
            ], 500);
        }
    }

    /**
     * Disable a user
     */
    public function disable(int $id): JsonResponse
    {
        if (!$this->hasPermission('users.edit')) {
            return response()->json([
                'message' => 'Anda tidak kebenaran untuk disable pengguna / You do not have permission to disable users',
            ], 403);
        }

        try {
            if ($id === auth()->id()) {
                return response()->json([
                    'message' => 'You cannot disable your own account',
                ], 403);
            }

            $user = User::findOrFail($id);
            $user->update(['is_active' => false]);

            return response()->json([
                'message' => 'User disabled successfully',
                'data' => $user->load('role'),
            ], 200);
        } catch (Throwable $e) {
            Log::error('User disable failed', ['id' => $id]);

            return response()->json([
                'message' => 'Failed to disable user',
            ], 500);
        }
    }

    /**
     * Enable a user.
     */
    public function enable(int $id): JsonResponse
    {
        if (!$this->hasPermission('users.edit')) {
            return response()->json([
                'message' => 'Anda tidak kebenaran untuk enable pengguna / You do not have permission to enable users',
            ], 403);
        }

        try {
            $user = User::findOrFail($id);
            $user->update(['is_active' => true]);

            return response()->json([
                'message' => 'User enabled successfully',
                'data' => $user->load('role'),
            ], 200);
        } catch (Throwable $e) {
            Log::error('User enable failed', ['id' => $id]);

            return response()->json([
                'message' => 'Failed to enable user',
            ], 500);
        }
    }

    /**
     * Block a user.
     */
    public function block(Request $request, int $id): JsonResponse
    {
        if (!$this->hasPermission('users.edit')) {
            return response()->json([
                'message' => 'Anda tidak kebenaran untuk block pengguna / You do not have permission to block users',
            ], 403);
        }

        try {
            if ($id === auth()->id()) {
                return response()->json([
                    'message' => 'You cannot block your own account',
                ], 403);
            }

            $validated = $request->validate([
                'reason' => 'nullable|string|max:500',
            ]);

            $user = User::findOrFail($id);
            $user->block($validated['reason'] ?? null);

            return response()->json([
                'message' => 'User blocked successfully',
                'data' => $user->load('role'),
            ], 200);
        } catch (Throwable $e) {
            Log::error('User block failed', ['id' => $id]);

            return response()->json([
                'message' => 'Failed to block user',
            ], 500);
        }
    }

    /**
     * Unblock a user.
     */
    public function unblock(int $id): JsonResponse
    {
        if (!$this->hasPermission('users.edit')) {
            return response()->json([
                'message' => 'Anda tidak kebenaran untuk unblock pengguna / You do not have permission to unblock users',
            ], 403);
        }

        try {
            $user = User::findOrFail($id);
            $user->unblock();

            return response()->json([
                'message' => 'User unblocked successfully',
                'data' => $user->load('role'),
            ], 200);
        } catch (Throwable $e) {
            Log::error('User unblock failed', ['id' => $id]);

            return response()->json([
                'message' => 'Failed to unblock user',
            ], 500);
        }
    }
}
