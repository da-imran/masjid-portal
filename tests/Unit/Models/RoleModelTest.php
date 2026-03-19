<?php

namespace Tests\Unit\Models;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Unit tests for Role model.
 *
 * Test Categories:
 * - Happy Paths: Verify the feature works as intended
 * - Negative Tests: Verify validation errors catch invalid data
 * - Access Control: Verify unauthorized user receive 403 forbidden
 * - Security Tests: Explicitly test security constraints
 */
class RoleModelTest extends TestCase
{
    use RefreshDatabase;

    /*
    |--------------------------------------------------------------------------
    | HAPPY PATHS - Verify the feature works as intended
    |--------------------------------------------------------------------------
    */

    /** @test */
    public function test_can_create_role(): void
    {
        $role = Role::create([
            'name' => 'Editor',
            'description' => 'Can edit content',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('roles', [
            'name' => 'Editor',
            'description' => 'Can edit content',
        ]);
        $this->assertInstanceOf(Role::class, $role);
    }

    /** @test */
    public function test_can_update_role(): void
    {
        $role = Role::factory()->create([
            'name' => 'Original Name',
        ]);

        $role->update(['name' => 'Updated Name']);

        $this->assertEquals('Updated Name', $role->fresh()->name);
    }

    /** @test */
    public function test_can_delete_role(): void
    {
        $role = Role::factory()->create();

        $roleId = $role->id;
        $role->delete();

        $this->assertDatabaseMissing('roles', ['id' => $roleId]);
    }

    /** @test */
    public function test_can_give_permission_to_role(): void
    {
        $role = Role::factory()->create();

        $role->givePermissionTo('edit-posts');

        $this->assertTrue($role->fresh()->hasPermission('edit-posts'));
        $this->assertDatabaseHas('permissions', [
            'role_id' => $role->id,
            'name' => 'edit-posts',
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | NEGATIVE TESTS - Verify validation errors catch invalid data
    |--------------------------------------------------------------------------
    */

    /** @test */
    public function test_cannot_create_role_without_name(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Role::create([
            'description' => 'Test Role',
        ]);
    }

    /** @test */
    public function test_cannot_create_duplicate_name(): void
    {
        Role::factory()->create(['name' => 'Editor']);

        $this->expectException(\Illuminate\Database\QueryException::class);

        Role::factory()->create(['name' => 'Editor']);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESS CONTROL - Verify unauthorized users receive 403 forbidden
    |--------------------------------------------------------------------------
    */

    // Note: Access control tests are in Feature/Api/RoleApiTest.php
    // Model tests focus on data layer, API layer handles authorization

    /*
    |--------------------------------------------------------------------------
    | SECURITY TESTS - Explicitly test security constraints
    |--------------------------------------------------------------------------
    */

    /** @test */
    public function security_test_name_is_unique(): void
    {
        Role::factory()->create(['name' => 'Editor']);

        $this->expectException(\Illuminate\Database\QueryException::class);

        Role::factory()->create(['name' => 'Editor']);
    }

    /** @test */
    public function security_test_permissions_control_access(): void
    {
        $role = Role::factory()->create();

        $role->givePermissionTo('edit-posts');

        $this->assertTrue($role->fresh()->hasPermission('edit-posts'));
        $this->assertFalse($role->fresh()->hasPermission('delete-posts'));
    }

    /** @test */
    public function security_test_can_revoke_permission(): void
    {
        $role = Role::factory()->create();
        $role->givePermissionTo('edit-posts');

        $this->assertTrue($role->hasPermission('edit-posts'));

        $role->revokePermissionTo('edit-posts');

        $this->assertFalse($role->fresh()->hasPermission('edit-posts'));
    }
}
