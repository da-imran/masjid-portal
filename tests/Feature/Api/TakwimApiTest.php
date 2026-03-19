<?php

namespace Tests\Feature\Api;

use App\Models\Takwim;

/**
 * Feature tests for Takwim API CRUD operations.
 *
 * Test Categories:
 * - Happy Paths: Verify the feature works as intended
 * - Negative Tests: Verify validation errors catch invalid data
 * - Access Control: Verify unauthorized users receive 403 forbidden
 * - Security Tests: Explicitly test security constraints
 */
class TakwimApiTest extends AuthTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    /*
    |--------------------------------------------------------------------------
    | HAPPY PATHS - Verify the feature works as intended
    |--------------------------------------------------------------------------
    */

    /** @test */
    public function test_admin_can_list_takwim(): void
    {
        Takwim::factory()->count(5)->create(['created_by' => $this->adminUser->id]);

        $response = $this->withToken($this->adminToken)
            ->getJson('/api/v1/admin/takwim');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title_ms', 'title_en', 'event_date', 'event_time', 'is_active']
                ],
                'meta' => ['current_page', 'last_page', 'per_page', 'total']
            ]);

        $this->assertCount(5, $response->json('data'));
    }

    /** @test */
    public function test_admin_can_create_takwim(): void
    {
        $takwimData = [
            'title_ms' => 'Program Ceramah',
            'title_en' => 'Lecture Program',
            'description_ms' => 'Deskripsi program',
            'description_en' => 'Program description',
            'event_date' => '2026-02-01',
            'event_time' => '14:30',
            'location_ms' => 'Dewan Utama',
            'location_en' => 'Main Hall',
            'is_active' => true,
        ];

        $response = $this->withToken($this->adminToken)
            ->postJson('/api/v1/admin/takwim', $takwimData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'data' => ['id', 'title_ms', 'event_date', 'event_time']
            ]);

        $this->assertDatabaseHas('takwim', [
            'title_ms' => 'Program Ceramah',
            'event_time' => '14:30:00',
        ]);
    }

    /** @test */
    public function test_admin_can_update_takwim(): void
    {
        $takwim = Takwim::factory()->create([
            'created_by' => $this->adminUser->id,
            'title_ms' => 'Original Title',
        ]);

        $response = $this->withToken($this->adminToken)
            ->putJson("/api/v1/admin/takwim/{$takwim->id}", [
                'title_ms' => 'Updated Title',
                'description_ms' => 'Updated description',
                'event_time' => '16:00',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Takwim updated successfully',
                'data' => [
                    'title_ms' => 'Updated Title',
                    'event_time' => '16:00:00',
                ]
            ]);
    }

    /** @test */
    public function test_admin_can_delete_takwim(): void
    {
        $takwim = Takwim::factory()->create(['created_by' => $this->adminUser->id]);

        $response = $this->withToken($this->adminToken)
            ->deleteJson("/api/v1/admin/takwim/{$takwim->id}");

        $response->assertStatus(200);

        $this->assertDatabaseHas('takwim', [
            'id' => $takwim->id,
            'is_deleted' => true,
        ]);
    }

    /** @test */
    public function test_admin_can_show_single_takwim(): void
    {
        $takwim = Takwim::factory()->create(['created_by' => $this->adminUser->id]);

        $response = $this->withToken($this->adminToken)
            ->getJson("/api/v1/admin/takwim/{$takwim->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $takwim->id,
                    'title_ms' => $takwim->title_ms,
                ]
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | NEGATIVE TESTS - Verify validation errors catch invalid data
    |--------------------------------------------------------------------------
    */

    /** @test */
    public function test_cannot_create_takwim_without_required_fields(): void
    {
        $response = $this->withToken($this->adminToken)
            ->postJson('/api/v1/admin/takwim', [
                'description_ms' => 'Test Description',
                // Missing title_ms and event_date
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title_ms', 'event_date']);
    }

    /** @test */
    public function test_cannot_create_takwim_with_invalid_time_format(): void
    {
        $response = $this->withToken($this->adminToken)
            ->postJson('/api/v1/admin/takwim', [
                'title_ms' => 'Test Program',
                'description_ms' => 'Test description',
                'event_date' => '2026-02-01',
                'event_time' => '25:00', // Invalid time
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['event_time']);
    }

    /** @test */
    public function test_cannot_create_takwim_with_invalid_date_format(): void
    {
        $response = $this->withToken($this->adminToken)
            ->postJson('/api/v1/admin/takwim', [
                'title_ms' => 'Test Program',
                'description_ms' => 'Test description',
                'event_date' => 'not-a-date',
                'event_time' => '14:30',
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['event_date']);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESS CONTROL - Verify unauthorized users receive 403 forbidden
    |--------------------------------------------------------------------------
    */

    /** @test */
    public function test_unauthenticated_user_cannot_access_takwim(): void
    {
        $response = $this->getJson('/api/v1/admin/takwim');

        $response->assertStatus(401);
    }

    /** @test */
    public function test_user_without_permission_cannot_create_takwim(): void
    {
        $response = $this->withToken($this->staffToken)
            ->postJson('/api/v1/admin/takwim', [
                'title_ms' => 'Test Title',
                'event_date' => '2026-02-01',
            ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function test_user_without_permission_cannot_update_takwim(): void
    {
        $takwim = Takwim::factory()->create(['created_by' => $this->adminUser->id]);

        $response = $this->withToken($this->staffToken)
            ->putJson("/api/v1/admin/takwim/{$takwim->id}", [
                'title_ms' => 'Updated Title',
            ]);

        $response->assertStatus(403);
    }

    /*
    |--------------------------------------------------------------------------
    | SECURITY TESTS - Explicitly test security constraints
    |--------------------------------------------------------------------------
    */

    /** @test */
    public function security_test_soft_delete_maintains_data_integrity(): void
    {
        $takwim = Takwim::factory()->create(['created_by' => $this->adminUser->id]);

        $response = $this->withToken($this->adminToken)
            ->deleteJson("/api/v1/admin/takwim/{$takwim->id}");

        $response->assertStatus(200);

        // Verify soft delete - record still exists but marked as deleted
        $this->assertDatabaseHas('takwim', [
            'id' => $takwim->id,
            'is_deleted' => true,
        ]);

        // Verify we can still access the record
        $foundTakwim = Takwim::find($takwim->id);
        $this->assertNotNull($foundTakwim);
        $this->assertTrue($foundTakwim->is_deleted);
    }

    /** @test */
    public function security_test_audit_trail_maintained_on_update(): void
    {
        $takwim = Takwim::factory()->create([
            'created_by' => $this->adminUser->id,
        ]);

        $response = $this->withToken($this->adminToken)
            ->putJson("/api/v1/admin/takwim/{$takwim->id}", [
                'title_ms' => 'Updated Title',
            ]);

        $response->assertStatus(200);

        // Verify updated_by is set
        $this->assertDatabaseHas('takwim', [
            'id' => $takwim->id,
            'updated_by' => $this->adminUser->id,
        ]);
    }

    /** @test */
    public function security_test_event_time_accepts_standard_html5_format(): void
    {
        $takwimData = [
            'title_ms' => 'Test Program',
            'description_ms' => 'Test program description',
            'event_date' => '2026-02-01',
            'event_time' => '14:30', // HTML5 time input format (HH:MM)
        ];

        $response = $this->withToken($this->adminToken)
            ->postJson('/api/v1/admin/takwim', $takwimData);

        $response->assertStatus(201);

        // Verify the time is stored correctly in HH:MM:SS format
        $this->assertDatabaseHas('takwim', [
            'event_time' => '14:30:00',
        ]);
    }
}
