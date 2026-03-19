<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Feature tests for User API CRUD operations.
 *
 * Test Categories:
 * - Happy Paths: Verify the feature works as intended
 * - Negative Tests: Verify validation errors catch invalid data
 * - Access Control: Verify unauthorized user receive 403 forbidden
 * - Security Tests: Explicitly test security constraints (e.g., User cannot delete themselves)
 */
class UserApiTest extends TestCase
{
    use RefreshDatabase;

    protected Role $adminRole;
    protected Role $staffRole;
    protected User $admin;
    protected string $adminToken;
    protected User $staffUser;
    protected string $staffToken;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminRole = Role::factory()->create(['name' => 'Admin', 'is_active' => true]);
        $this->staffRole = Role::factory()->create(['name' => 'Staff', 'is_active' => true]);

        $this->admin = User::factory()->create([
            'role_id' => $this->adminRole->id,
            'is_active' => true,
        ]);

        $this->adminToken = $this->admin->createToken('test-token')->plainTextToken;

        $this->staffUser = User::factory()->create([
            'role_id' => $this->staffRole->id,
            'is_active' => true,
        ]);

        $this->staffToken = $this->staffUser->createToken('staff-token')->plainTextToken;
    }

    /*
    |--------------------------------------------------------------------------
    | HAPPY PATHS - Verify the feature works as intended
    |--------------------------------------------------------------------------
    */

    /** @test */
    public function test_admin_can_list_users(): void
    {
        User::factory()->count(3)->create(['role_id' => $this->staffRole->id]);

        $response = $this->withToken($this->adminToken)
            ->getJson('/api/v1/admin/users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'email', 'role_id', 'is_active']
                ],
            ]);

        $this->assertCount(5, $response->json('data')); // 3 + admin + staffUser
    }

    /** @test */
    public function test_admin_can_create_user(): void
    {
        $userData = [
            'name' => 'New User',
            'email' => 'newuser@test.com',
            'password' => 'password123',
            'role_id' => $this->staffRole->id,
        ];

        $response = $this->withToken($this->adminToken)
            ->postJson('/api/v1/admin/users', $userData);

        $response->assertStatus(201);

        $this->assertDatabaseHas('users', [
            'email' => 'newuser@test.com',
            'name' => 'New User',
        ]);
    }

    /** @test */
    public function test_admin_can_update_user(): void
    {
        $user = User::factory()->create(['role_id' => $this->staffRole->id]);

        $response = $this->withToken($this->adminToken)
            ->putJson("/api/v1/admin/users/{$user->id}", [
                'name' => 'Updated Name',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'User updated successfully',
                'data' => ['name' => 'Updated Name']
            ]);
    }

    /** @test */
    public function test_admin_can_delete_user(): void
    {
        $user = User::factory()->create(['role_id' => $this->staffRole->id]);

        $response = $this->withToken($this->adminToken)
            ->deleteJson("/api/v1/admin/users/{$user->id}");

        $response->assertStatus(200);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | NEGATIVE TESTS - Verify validation errors catch invalid data
    |--------------------------------------------------------------------------
    */

    /** @test */
    public function test_cannot_create_user_without_required_fields(): void
    {
        $response = $this->withToken($this->adminToken)
            ->postJson('/api/v1/admin/users', [
                'name' => 'Test User',
                // Missing email, password
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password']);
    }

    /** @test */
    public function test_cannot_create_user_with_weak_password(): void
    {
        $response = $this->withToken($this->adminToken)
            ->postJson('/api/v1/admin/users', [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => '123',
                'role_id' => $this->staffRole->id,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['password']);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESS CONTROL - Verify unauthorized users receive 403 forbidden
    |--------------------------------------------------------------------------
    */

    /** @test */
    public function test_unauthenticated_user_cannot_access_users(): void
    {
        $response = $this->getJson('/api/v1/admin/users');

        $response->assertStatus(401);
    }

    /** @test */
    public function test_non_admin_cannot_access_users(): void
    {
        $response = $this->withToken($this->staffToken)
            ->getJson('/api/v1/admin/users');

        $response->assertStatus(403);
    }

    /*
    |--------------------------------------------------------------------------
    | SECURITY TESTS - Explicitly test security constraints
    |--------------------------------------------------------------------------
    */

    /** @test */
    public function security_test_user_cannot_delete_own_account(): void
    {
        $response = $this->withToken($this->adminToken)
            ->deleteJson("/api/v1/admin/users/{$this->admin->id}");

        $response->assertStatus(403)
            ->assertJson([
                'message' => 'You cannot delete your own account.',
            ]);
    }

    /** @test */
    public function security_test_password_is_hashed(): void
    {
        $response = $this->withToken($this->adminToken)
            ->postJson('/api/v1/admin/users', [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => 'plaintext-password',
                'role_id' => $this->staffRole->id,
            ]);

        $response->assertStatus(201);

        $user = User::where('email', 'test@example.com')->first();
        $this->assertNotEquals('plaintext-password', $user->password);
        $this->assertTrue(\Hash::check('plaintext-password', $user->password));
    }

    /** @test */
    public function security_test_email_must_be_unique(): void
    {
        User::factory()->create(['email' => 'test@example.com']);

        $response = $this->withToken($this->adminToken)
            ->postJson('/api/v1/admin/users', [
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => 'password123',
                'role_id' => $this->staffRole->id,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }
}
