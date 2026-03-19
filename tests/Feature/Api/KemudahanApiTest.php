<?php

namespace Tests\Feature\Api;

use App\Models\Kemudahan;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Feature tests for Kemudahan API CRUD operations.
 *
 * Test Categories:
 * - Happy Paths: Verify the feature works as intended
 * - Negative Tests: Verify validation errors catch invalid data
 * - Access Control: Verify unauthorized users receive 403 forbidden
 * - Security Tests: Explicitly test security constraints
 */
class KemudahanApiTest extends AuthTestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Storage::fake('public');
    }

    /*
    |--------------------------------------------------------------------------
    | HAPPY PATHS - Verify the feature works as intended
    |--------------------------------------------------------------------------
    */

    /** @test */
    public function test_admin_can_list_kemudahan(): void
    {
        Kemudahan::factory()->count(5)->create(['created_by' => $this->adminUser->id]);

        $response = $this->withToken($this->adminToken)
            ->getJson('/api/v1/admin/kemudahan');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title_ms', 'title_en', 'image_name', 'is_active', 'order_column']
                ],
                'meta' => ['current_page', 'last_page', 'per_page', 'total']
            ]);

        $this->assertCount(5, $response->json('data'));
    }

    /** @test */
    public function test_admin_can_create_kemudahan(): void
    {
        $image = UploadedFile::fake()->image('facility.jpg', 800, 600);

        $kemudahanData = [
            'title_ms' => 'Kemudahan Dewan',
            'title_en' => 'Hall Facility',
            'description_ms' => 'Deskripsi kemudahan',
            'description_en' => 'Facility description',
            'image' => $image,
            'order_column' => 1,
            'is_active' => true,
        ];

        $response = $this->withToken($this->adminToken)
            ->postJson('/api/v1/admin/kemudahan', $kemudahanData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'data' => ['id', 'title_ms', 'image_name']
            ]);

        $this->assertDatabaseHas('kemudahan', [
            'title_ms' => 'Kemudahan Dewan',
        ]);
    }

    /** @test */
    public function test_admin_can_update_kemudahan(): void
    {
        $kemudahan = Kemudahan::factory()->create([
            'created_by' => $this->adminUser->id,
            'title_ms' => 'Original Title',
        ]);

        $response = $this->withToken($this->adminToken)
            ->putJson("/api/v1/admin/kemudahan/{$kemudahan->id}", [
                'title_ms' => 'Updated Title',
                'description_ms' => 'Updated description',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Kemudahan updated successfully',
                'data' => ['title_ms' => 'Updated Title']
            ]);
    }

    /** @test */
    public function test_admin_can_delete_kemudahan(): void
    {
        $kemudahan = Kemudahan::factory()->create(['created_by' => $this->adminUser->id]);

        $response = $this->withToken($this->adminToken)
            ->deleteJson("/api/v1/admin/kemudahan/{$kemudahan->id}");

        $response->assertStatus(200);

        $this->assertDatabaseHas('kemudahan', [
            'id' => $kemudahan->id,
            'is_deleted' => true,
        ]);
    }

    /** @test */
    public function test_admin_can_show_single_kemudahan(): void
    {
        $kemudahan = Kemudahan::factory()->create(['created_by' => $this->adminUser->id]);

        $response = $this->withToken($this->adminToken)
            ->getJson("/api/v1/admin/kemudahan/{$kemudahan->id}");

        $response->assertStatus(200)
            ->assertJson([
                'data' => [
                    'id' => $kemudahan->id,
                    'title_ms' => $kemudahan->title_ms,
                ]
            ]);
    }

    /*
    |--------------------------------------------------------------------------
    | NEGATIVE TESTS - Verify validation errors catch invalid data
    |--------------------------------------------------------------------------
    */

    /** @test */
    public function test_cannot_create_kemudahan_without_required_fields(): void
    {
        $response = $this->withToken($this->adminToken)
            ->postJson('/api/v1/admin/kemudahan', [
                'description_ms' => 'Test Description',
                // Missing title_ms
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['title_ms']);
    }

    /** @test */
    public function test_cannot_create_kemudahan_with_invalid_image(): void
    {
        $file = UploadedFile::fake()->create('document.pdf', 1000);

        $response = $this->withToken($this->adminToken)
            ->postJson('/api/v1/admin/kemudahan', [
                'title_ms' => 'Test Title',
                'image' => $file,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['image']);
    }

    /** @test */
    public function test_cannot_create_kemudahan_with_invalid_order_column(): void
    {
        $response = $this->withToken($this->adminToken)
            ->postJson('/api/v1/admin/kemudahan', [
                'title_ms' => 'Test Title',
                'order_column' => -1,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['order_column']);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESS CONTROL - Verify unauthorized users receive 403 forbidden
    |--------------------------------------------------------------------------
    */

    /** @test */
    public function test_unauthenticated_user_cannot_access_kemudahan(): void
    {
        $response = $this->getJson('/api/v1/admin/kemudahan');

        $response->assertStatus(401);
    }

    /** @test */
    public function test_user_without_permission_cannot_create_kemudahan(): void
    {
        $response = $this->withToken($this->staffToken)
            ->postJson('/api/v1/admin/kemudahan', [
                'title_ms' => 'Test Title',
                'description_ms' => 'Test description',
            ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function test_user_without_permission_cannot_update_kemudahan(): void
    {
        $kemudahan = Kemudahan::factory()->create(['created_by' => $this->adminUser->id]);

        $response = $this->withToken($this->staffToken)
            ->putJson("/api/v1/admin/kemudahan/{$kemudahan->id}", [
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
    public function security_test_file_upload_restricts_to_images_only(): void
    {
        $file = UploadedFile::fake()->create('malicious.exe', 1000);

        $response = $this->withToken($this->adminToken)
            ->postJson('/api/v1/admin/kemudahan', [
                'title_ms' => 'Test Title',
                'description_ms' => 'Test description',
                'image' => $file,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['image']);
    }

    /** @test */
    public function security_test_soft_delete_maintains_data_integrity(): void
    {
        $kemudahan = Kemudahan::factory()->create(['created_by' => $this->adminUser->id]);

        $response = $this->withToken($this->adminToken)
            ->deleteJson("/api/v1/admin/kemudahan/{$kemudahan->id}");

        $response->assertStatus(200);

        // Verify soft delete - record still exists but marked as deleted
        $this->assertDatabaseHas('kemudahan', [
            'id' => $kemudahan->id,
            'is_deleted' => true,
        ]);

        // Verify we can still access the record
        $foundKemudahan = Kemudahan::find($kemudahan->id);
        $this->assertNotNull($foundKemudahan);
        $this->assertTrue($foundKemudahan->is_deleted);
    }

    /** @test */
    public function security_test_audit_trail_maintained_on_update(): void
    {
        $kemudahan = Kemudahan::factory()->create([
            'created_by' => $this->adminUser->id,
        ]);

        $response = $this->withToken($this->adminToken)
            ->putJson("/api/v1/admin/kemudahan/{$kemudahan->id}", [
                'title_ms' => 'Updated Title',
            ]);

        $response->assertStatus(200);

        // Verify updated_by is set
        $this->assertDatabaseHas('kemudahan', [
            'id' => $kemudahan->id,
            'updated_by' => $this->adminUser->id,
        ]);
    }
}
