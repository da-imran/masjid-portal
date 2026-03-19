<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Login user and return token with user data.
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            \Log::warning('Login validation failed', [
                'errors' => $validator->errors()->toArray(),
                'input' => $request->all(),
            ]);

            return response()->json([
                'error' => 'Validation failed',
                'messages' => $validator->errors(),
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        // Check if user is blocked
        if ($user && $user->is_blocked) {
            return response()->json([
                'error' => 'Account blocked',
                'message' => $user->blocked_reason ?? 'Your account has been blocked.',
            ], 403);
        }

        // Check if user is disabled
        if ($user && !$user->is_active) {
            return response()->json([
                'error' => 'Account disabled',
                'message' => 'Your account has been disabled.',
            ], 403);
        }

        // Attempt authentication
        if (!Auth::guard('web')->attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user = Auth::guard('web')->user();

        // Create API token
        $token = $user->createToken('api-token')->plainTextToken;

        // Load role and permissions
        $user->load(['role', 'role.permissions']);

        return response()->json([
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role ? [
                    'id' => $user->role->id,
                    'name' => $user->role->name,
                ] : null,
                'permissions' => $user->role && $user->role->permissions
                    ? $user->role->permissions->pluck('name')->toArray()
                    : [],
            ],
        ], 200);
    }

    /**
     * Logout user and revoke token.
     */
    public function logout(Request $request): JsonResponse
    {
        // Revoke the current user's token
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Successfully logged out',
        ], 200);
    }

    /**
     * Get authenticated user with permissions.
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();

        // Load role and permissions
        $user->load(['role', 'role.permissions']);

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role ? [
                    'id' => $user->role->id,
                    'name' => $user->role->name,
                ] : null,
                'permissions' => $user->role && $user->role->permissions
                    ? $user->role->permissions->pluck('name')->toArray()
                    : [],
            ],
        ], 200);
    }

    /**
     * Refresh authentication token.
     */
    public function refresh(Request $request): JsonResponse
    {
        $user = $request->user();

        // Revoke current token
        $user->currentAccessToken()->delete();

        // Create new token
        $token = $user->createToken('api-token')->plainTextToken;

        // Load role and permissions
        $user->load(['role', 'role.permissions']);

        return response()->json([
            'token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role ? [
                    'id' => $user->role->id,
                    'name' => $user->role->name,
                ] : null,
                'permissions' => $user->role && $user->role->permissions
                    ? $user->role->permissions->pluck('name')->toArray()
                    : [],
            ],
        ], 200);
    }
}
