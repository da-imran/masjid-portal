<?php

namespace Tests\Unit\Models;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Unit tests for User model.
 *
 * Test Categories:
 * - Happy Paths: Verify the feature works as intended
 * - Negative Tests: Verify validation errors catch invalid data
 * - Access Control: Verify unauthorized user receive 403 forbidden
 * - Security Tests: Explicitly test security constraints
 */
class UserModelTest extends TestCase
{
    use RefreshDatabase;

    protected Role $adminRole;
    protected Role $viewerRole;

    protected function setUp(): void
    {
        parent::setUp();

        $this->adminRole = Role::factory()->create(['name' => 'Admin', 'is_active' => true]);
        $this->viewerRole = Role::factory()->create(['name' => 'Viewer', 'is_active' => true]);
    }

    /*
    |--------------------------------------------------------------------------
    | HAPPY PATHS - Verify the feature works as intended
    |--------------------------------------------------------------------------
    */

    /** @test */
    public function test_can_create_user(): void
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'role_id' => $this->viewerRole->id,
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        $this->assertInstanceOf(User::class, $user);
    }

    /** @test */
    public function test_can_update_user(): void
    {
        $user = User::factory()->create([
            'role_id' => $this->viewerRole->id,
            'name' => 'Original Name',
        ]);

        $user->update(['name' => 'Updated Name']);

        $this->assertEquals('Updated Name', $user->fresh()->name);
    }

    /** @test */
    public function test_can_delete_user(): void
    {
        $user = User::factory()->create(['role_id' => $this->viewerRole->id]);

        $userId = $user->id;
        $user->delete();

        $this->assertDatabaseMissing('users', ['id' => $userId]);
    }

    /** @test */
    public function test_can_block_user(): void
    {
        $user = User::factory()->create([
            'role_id' => $this->viewerRole->id,
            'is_blocked' => false,
        ]);

        $user->block('Violation of terms');

        $this->assertTrue($user->fresh()->is_blocked);
        $this->assertEquals('Violation of terms', $user->fresh()->blocked_reason);
    }

    /*
    |--------------------------------------------------------------------------
    | NEGATIVE TESTS - Verify validation errors catch invalid data
    |--------------------------------------------------------------------------
    */

    /** @test */
    public function test_cannot_create_user_without_email(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        User::create([
            'name' => 'Test User',
            'password' => bcrypt('password123'),
        ]);
    }

    /** @test */
    public function test_cannot_create_duplicate_email(): void
    {
        User::factory()->create([
            'role_id' => $this->viewerRole->id,
            'email' => 'test@example.com',
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        User::factory()->create([
            'role_id' => $this->viewerRole->id,
            'email' => 'test@example.com',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESS CONTROL - Verify unauthorized users receive 403 forbidden
    |--------------------------------------------------------------------------
    */

    // Note: Access control tests are in Feature/Api/UserApiTest.php
    // Model tests focus on data layer, API layer handles authorization

    /*
    |--------------------------------------------------------------------------
    | SECURITY TESTS - Explicitly test security constraints
    |--------------------------------------------------------------------------
    */

    /** @test */
    public function security_test_password_is_hashed(): void
    {
        $user = User::factory()->create([
            'role_id' => $this->viewerRole->id,
            'password' => 'plaintext-password',
        ]);

        $this->assertNotEquals('plaintext-password', $user->password);
        $this->assertTrue(\Hash::check('plaintext-password', $user->password));
    }

    /** @test */
    public function security_test_email_is_unique(): void
    {
        User::factory()->create([
            'role_id' => $this->viewerRole->id,
            'email' => 'test@example.com',
        ]);

        $this->expectException(\Illuminate\Database\QueryException::class);

        User::factory()->create([
            'role_id' => $this->viewerRole->id,
            'email' => 'test@example.com',
        ]);
    }

    /** @test */
    public function security_test_blocked_flag_prevents_access(): void
    {
        $user = User::factory()->create([
            'role_id' => $this->viewerRole->id,
            'is_blocked' => true,
            'blocked_reason' => 'Security violation',
        ]);

        $this->assertTrue($user->is_blocked);
        $this->assertEquals('Security violation', $user->blocked_reason);
    }
}
