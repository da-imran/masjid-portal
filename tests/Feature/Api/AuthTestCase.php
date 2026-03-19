<?php

namespace Tests\Feature\Api;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

abstract class AuthTestCase extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected User $adminUser;

    protected User $staffUser;

    protected User $viewerUser;

    protected string $adminToken;

    protected string $staffToken;

    protected string $viewerToken;

    protected function setUp(): void
    {
        parent::setUp();

        // Create roles
        $adminRole = Role::factory()->create(['name' => 'Admin', 'is_active' => true]);
        $staffRole = Role::factory()->create(['name' => 'Staff', 'is_active' => true]);
        $viewerRole = Role::factory()->create(['name' => 'Viewer', 'is_active' => true]);

        // Create permissions for admin role (all permissions)
        $adminPermissions = [
            ['name' => 'users.view', 'description' => 'View Users'],
            ['name' => 'users.create', 'description' => 'Create Users'],
            ['name' => 'users.edit', 'description' => 'Edit Users'],
            ['name' => 'users.delete', 'description' => 'Delete Users'],
            ['name' => 'roles.view', 'description' => 'View Roles'],
            ['name' => 'roles.create', 'description' => 'Create Roles'],
            ['name' => 'roles.edit', 'description' => 'Edit Roles'],
            ['name' => 'roles.delete', 'description' => 'Delete Roles'],
            ['name' => 'permissions.view', 'description' => 'View Permissions'],
            ['name' => 'permissions.create', 'description' => 'Create Permissions'],
            ['name' => 'permissions.edit', 'description' => 'Edit Permissions'],
            ['name' => 'permissions.delete', 'description' => 'Delete Permissions'],
            ['name' => 'berita.view', 'description' => 'View Berita'],
            ['name' => 'berita.create', 'description' => 'Create Berita'],
            ['name' => 'berita.edit', 'description' => 'Edit Berita'],
            ['name' => 'berita.delete', 'description' => 'Delete Berita'],
            ['name' => 'kemudahan.view', 'description' => 'View Kemudahan'],
            ['name' => 'kemudahan.create', 'description' => 'Create Kemudahan'],
            ['name' => 'kemudahan.edit', 'description' => 'Edit Kemudahan'],
            ['name' => 'kemudahan.delete', 'description' => 'Delete Kemudahan'],
            ['name' => 'takwim.view', 'description' => 'View Takwim'],
            ['name' => 'takwim.create', 'description' => 'Create Takwim'],
            ['name' => 'takwim.edit', 'description' => 'Edit Takwim'],
            ['name' => 'takwim.delete', 'description' => 'Delete Takwim'],
        ];

        foreach ($adminPermissions as $permission) {
            Permission::factory()->create([
                'role_id' => $adminRole->id,
                'name' => $permission['name'],
                'description' => $permission['description'],
                'is_active' => true,
            ]);
        }

        // Give staff limited permissions (read-only)
        $staffPermissions = [
            ['name' => 'berita.view', 'description' => 'View Berita'],
            ['name' => 'kemudahan.view', 'description' => 'View Kemudahan'],
            ['name' => 'takwim.view', 'description' => 'View Takwim'],
        ];

        foreach ($staffPermissions as $permission) {
            Permission::factory()->create([
                'role_id' => $staffRole->id,
                'name' => $permission['name'],
                'description' => $permission['description'],
                'is_active' => true,
            ]);
        }

        // Give viewer read-only permissions
        $viewerPermissions = [
            ['name' => 'berita.view', 'description' => 'View Berita'],
            ['name' => 'kemudahan.view', 'description' => 'View Kemudahan'],
            ['name' => 'takwim.view', 'description' => 'View Takwim'],
        ];

        foreach ($viewerPermissions as $permission) {
            Permission::factory()->create([
                'role_id' => $viewerRole->id,
                'name' => $permission['name'],
                'description' => $permission['description'],
                'is_active' => true,
            ]);
        }

        // Create users with different roles
        $this->adminUser = User::factory()->create([
            'email' => 'admin@test.com',
            'role_id' => $adminRole->id,
            'is_active' => true,
        ]);

        $this->staffUser = User::factory()->create([
            'email' => 'staff@test.com',
            'role_id' => $staffRole->id,
            'is_active' => true,
        ]);

        $this->viewerUser = User::factory()->create([
            'email' => 'viewer@test.com',
            'role_id' => $viewerRole->id,
            'is_active' => true,
        ]);

        // Create tokens for each user
        $this->adminToken = $this->adminUser->createToken('admin-test-token')->plainTextToken;
        $this->staffToken = $this->staffUser->createToken('staff-test-token')->plainTextToken;
        $this->viewerToken = $this->viewerUser->createToken('viewer-test-token')->plainTextToken;
    }

    /**
     * Get authenticated headers for a given token.
     */
    protected function withAuth(string $token): array
    {
        return [
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ];
    }

    /**
     * Get admin authenticated headers.
     */
    protected function asAdmin(): array
    {
        return $this->withAuth($this->adminToken);
    }

    /**
     * Get staff authenticated headers.
     */
    protected function asStaff(): array
    {
        return $this->withAuth($this->staffToken);
    }

    /**
     * Get viewer authenticated headers.
     */
    protected function asViewer(): array
    {
        return $this->withAuth($this->viewerToken);
    }
}
