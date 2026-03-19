<?php

namespace Tests\Feature\Api;

use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
 * Feature tests for Role API CRUD operations.
 * Tests all endpoints with success and failure scenarios.
 */
class RoleApiTest extends AuthTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function it_can_list_roles_as_admin(): void
    {
        Role::factory()->count(3)->create();

        $response = $this->withToken($this->adminToken)
            ->getJson('/api/v1/admin/roles');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'description', 'is_active', 'created_at', 'updated_at']
                ],
                'meta' => ['current_page', 'last_page', 'per_page', 'total']
            ]);

        $this->assertCount(6, $response->json('data')); // 3 factory + 3 from AuthTestCase (admin, staff, viewer)
    }

    /** @test */
    public function it_fails_to_list_roles_without_authentication(): void
    {
        $response = $this->getJson('/api/v1/admin/roles');

        $response->assertStatus(401);
    }

    /** @test */
    public function it_can_show_single_role(): void
    {
        $role = Role::factory()->create();

        $response = $this->withToken($this->adminToken)
            ->getJson("/api/v1/admin/roles/{$role->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $role->id,
                    'name' => $role->name,
                    'description' => $role->description,
                ]
            ]);
    }

    /** @test */
    public function it_fails_to_show_nonexistent_role(): void
    {
        $response = $this->withToken($this->adminToken)
            ->getJson('/api/v1/admin/roles/9999');

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_create_role(): void
    {
        $roleData = [
            'name' => 'Editor',
            'description' => 'Can edit content',
            'is_active' => true,
        ];

        $response = $this->withToken($this->adminToken)
            ->postJson('/api/v1/admin/roles', $roleData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'data' => ['id', 'name', 'description']
            ]);

        $this->assertDatabaseHas('roles', [
            'name' => 'Editor',
            'description' => 'Can edit content',
        ]);
    }

    /** @test */
    public function it_fails_to_create_role_without_required_fields(): void
    {
        $roleData = [
            'description' => 'Test Role',
            // Missing name
        ];

        $response = $this->withToken($this->adminToken)
            ->postJson('/api/v1/admin/roles', $roleData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    /** @test */
    public function it_fails_to_create_role_with_duplicate_name(): void
    {
        Role::factory()->create(['name' => 'Editor']);

        $roleData = [
            'name' => 'Editor',
            'description' => 'Another editor',
        ];

        $response = $this->withToken($this->adminToken)
            ->postJson('/api/v1/admin/roles', $roleData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    /** @test */
    public function it_can_update_role(): void
    {
        $role = Role::factory()->create(['name' => 'Original Name']);

        $updateData = [
            'name' => 'Updated Name',
            'description' => 'Updated description',
        ];

        $response = $this->withToken($this->adminToken)
            ->putJson("/api/v1/admin/roles/{$role->id}", $updateData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Role updated successfully',
                'data' => [
                    'name' => 'Updated Name',
                ]
            ]);

        $this->assertDatabaseHas('roles', [
            'id' => $role->id,
            'name' => 'Updated Name',
        ]);
    }

    /** @test */
    public function it_fails_to_update_role_with_duplicate_name(): void
    {
        $role1 = Role::factory()->create();
        $role2 = Role::factory()->create();

        $updateData = [
            'name' => $role2->name, // Use another role's name (duplicate)
        ];

        $response = $this->withToken($this->adminToken)
            ->putJson("/api/v1/admin/roles/{$role1->id}", $updateData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name']);
    }

    /** @test */
    public function it_can_delete_role(): void
    {
        $role = Role::factory()->create();

        $response = $this->withToken($this->adminToken)
            ->deleteJson("/api/v1/admin/roles/{$role->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Role deleted successfully',
            ]);

        $this->assertDatabaseMissing('roles', ['id' => $role->id]);
    }

    /** @test */
    public function it_fails_to_delete_nonexistent_role(): void
    {
        $response = $this->withToken($this->adminToken)
            ->deleteJson('/api/v1/admin/roles/9999');

        $response->assertStatus(404);
    }

    /** @test */
    public function it_can_filter_roles_by_search(): void
    {
        Role::factory()->create(['name' => 'Content Editor']);
        Role::factory()->create(['name' => 'Forum Moderator']);
        Role::factory()->create(['name' => 'User Manager']);

        $response = $this->withToken($this->adminToken)
            ->getJson('/api/v1/admin/roles?search=Editor');

        $response->assertStatus(200);

        $data = $response->json('data');
        $this->assertCount(1, $data);
        $this->assertEquals('Content Editor', $data[0]['name']);
    }

    /** @test */
    public function it_can_paginate_roles(): void
    {
        Role::factory()->count(25)->create();

        $response = $this->withToken($this->adminToken)
            ->getJson('/api/v1/admin/roles?per_page=10&page=2');

        $response->assertStatus(200)
            ->assertJson([
                'meta' => [
                    'current_page' => 2,
                    'per_page' => 10,
                ]
            ]);

        $this->assertCount(10, $response->json('data'));
    }
}
