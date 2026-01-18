<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request): JsonResponse
    {
        $query = User::with('role');

        // Filter by role
        if ($request->has('role_id')) {
            $query->where('role_id', $request->role_id);
        }

        // Filter by status
        if ($request->has('is_blocked')) {
            $query->where('is_blocked', $request->boolean('is_blocked'));
        }

        // Search by name or email
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        // Pagination
        $perPage = $request->get('per_page', 15);
        $users = $query->paginate($perPage);

        return response()->json([
            'data' => $users->items(),
            'meta' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ],
        ], 200);
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role_id' => 'nullable|exists:roles,id',
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $request->role_id,
            'is_active' => false,
        ]);

        // Load role relationship
        $user->load('role');

        return response()->json([
            'message' => 'User created successfully',
            'data' => $user,
        ], 201);
    }

    /**
     * Display the specified user.
     */
    public function show(int $id): JsonResponse
    {
        $user = User::with('role')->findOrFail($id);

        return response()->json([
            'data' => $user,
        ], 200);
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $user = User::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'email' => ['sometimes', 'required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8',
            'role_id' => 'nullable|exists:roles,id',
            'is_active' => 'nullable|boolean',
            'is_blocked' => 'nullable|boolean',
            'blocked_reason' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            throw ValidationException::withMessages($validator->errors()->toArray());
        }

        $updateData = [
            'name' => $request->name ?? $user->name,
            'email' => $request->email ?? $user->email,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        if ($request->has('role_id')) {
            $updateData['role_id'] = $request->role_id;
        }

        if ($request->has('is_active')) {
            $updateData['is_active'] = $request->boolean('is_active');
        }

        if ($request->has('is_blocked')) {
            $updateData['is_blocked'] = $request->boolean('is_blocked');
            if ($request->boolean('is_blocked')) {
                $updateData['blocked_at'] = now();
            } else {
                $updateData['blocked_at'] = null;
                $updateData['blocked_reason'] = null;
            }
        }

        if ($request->has('blocked_reason') && $request->boolean('is_blocked')) {
            $updateData['blocked_reason'] = $request->blocked_reason;
        }

        $user->update($updateData);
        $user->load('role');

        return response()->json([
            'message' => 'User updated successfully',
            'data' => $user,
        ], 200);
    }

    /**
     * Remove the specified user (soft delete - set is_deleted to true).
     */
    public function destroy(int $id): JsonResponse
    {
        $user = User::findOrFail($id);

        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return response()->json([
                'error' => 'Forbidden',
                'message' => 'You cannot delete your own account.',
            ], 403);
        }

        // Soft delete - set is_deleted to true
        $user->is_deleted = true;
        $user->save();

        return response()->json([
            'message' => 'User deleted successfully',
            'data' => $user->load('role'),
        ], 200);
    }

    /**
     * Block a user.
     */
    public function block(Request $request, int $id): JsonResponse
    {
        $user = User::findOrFail($id);

        // Prevent blocking yourself
        if ($user->id === auth()->id()) {
            return response()->json([
                'error' => 'Forbidden',
                'message' => 'You cannot block your own account.',
            ], 403);
        }

        $request->validate([
            'reason' => 'nullable|string|max:500',
        ]);

        $user->block($request->reason);

        return response()->json([
            'message' => 'User blocked successfully',
            'data' => $user->load('role'),
        ], 200);
    }

    /**
     * Unblock a user.
     */
    public function unblock(int $id): JsonResponse
    {
        $user = User::findOrFail($id);
        $user->unblock();

        return response()->json([
            'message' => 'User unblocked successfully',
            'data' => $user->load('role'),
        ], 200);
    }
}
