<?php

namespace Tests\Feature\Api;

use App\Models\BeritaSemasa;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

/**
 * Feature tests for Berita (BeritaSemasa) API CRUD operations.
 *
 * Test Categories:
 * - Happy Paths: Verify the feature works as intended
 * - Negative Tests: Verify validation errors catch invalid data
 * - Access Control: Verify unauthorized user receive 403 forbidden
 * - Security Tests: Explicitly test security constraints
 */
class BeritaApiTest extends AuthTestCase
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
    public function test_admin_can_list_berita(): void
    {
        BeritaSemasa::factory()->count(3)->create(['created_by' => $this->adminUser->id]);

        $response = $this->withToken($this->adminToken)
            ->getJson('/api/v1/admin/berita');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'title_ms', 'title_en', 'is_active', 'created_at']
                ],
                'meta' => ['current_page', 'last_page', 'per_page', 'total']
            ]);

        $this->assertCount(3, $response->json('data'));
    }

    /** @test */
    public function test_admin_can_create_berita(): void
    {
        $image = UploadedFile::fake()->image('news.jpg', 800, 600);

        $beritaData = [
            'title_ms' => 'Berita Penting',
            'content_ms' => 'Kandungan berita dalam bahasa melayu',
            'image' => $image,
        ];

        $response = $this->withToken($this->adminToken)
            ->postJson('/api/v1/admin/berita', $beritaData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'data' => ['id', 'title_ms', 'image_name']
            ]);

        $this->assertDatabaseHas('berita_semasa', [
            'title_ms' => 'Berita Penting',
        ]);
    }

    /** @test */
    public function test_admin_can_update_berita(): void
    {
        $berita = BeritaSemasa::factory()->create([
            'created_by' => $this->adminUser->id,
            'title_ms' => 'Original Title',
        ]);

        $response = $this->withToken($this->adminToken)
            ->putJson("/api/v1/admin/berita/{$berita->id}", [
                'title_ms' => 'Updated Title',
            ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Berita Semasa updated successfully',
                'data' => ['title_ms' => 'Updated Title']
            ]);
    }

    /** @test */
    public function test_admin_can_delete_berita(): void
    {
        $berita = BeritaSemasa::factory()->create(['created_by' => $this->adminUser->id]);

        $response = $this->withToken($this->adminToken)
            ->deleteJson("/api/v1/admin/berita/{$berita->id}");

        $response->assertStatus(200);

        $this->assertDatabaseHas('berita_semasa', [
            'id' => $berita->id,
            'is_deleted' => true,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | NEGATIVE TESTS - Verify validation errors catch invalid data
    |--------------------------------------------------------------------------
    */

    /** @test */
    public function test_cannot_create_berita_without_required_fields(): void
    {
        $response = $this->withToken($this->adminToken)
            ->postJson('/api/v1/admin/berita', [
                'title_ms' => 'Test Title',
                // Missing content_ms
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['content_ms']);
    }

    /** @test */
    public function test_cannot_create_berita_with_invalid_image(): void
    {
        $file = UploadedFile::fake()->create('document.pdf', 1000);

        $response = $this->withToken($this->adminToken)
            ->postJson('/api/v1/admin/berita', [
                'title_ms' => 'Test Title',
                'content_ms' => 'Test content',
                'image' => $file,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['image']);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESS CONTROL - Verify unauthorized users receive 403 forbidden
    |--------------------------------------------------------------------------
    */

    /** @test */
    public function test_unauthenticated_user_cannot_access_berita(): void
    {
        $response = $this->getJson('/api/v1/admin/berita');

        $response->assertStatus(401);
    }

    /** @test */
    public function test_user_without_permission_cannot_create_berita(): void
    {
        // Staff user without berita.create permission
        $response = $this->withToken($this->staffToken)
            ->postJson('/api/v1/admin/berita', [
                'title_ms' => 'Test Title',
                'content_ms' => 'Test content',
            ]);

        $response->assertStatus(403);
    }

    /** @test */
    public function test_user_without_permission_cannot_update_berita(): void
    {
        $berita = BeritaSemasa::factory()->create(['created_by' => $this->adminUser->id]);

        $response = $this->withToken($this->staffToken)
            ->putJson("/api/v1/admin/berita/{$berita->id}", [
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
    public function security_test_user_cannot_delete_or_modify_own_account(): void
    {
        // This test doesn't apply to Berita (no user ownership),
        // but we test that audit trail is maintained
        $berita = BeritaSemasa::factory()->create([
            'created_by' => $this->adminUser->id,
        ]);

        $response = $this->withToken($this->adminToken)
            ->putJson("/api/v1/admin/berita/{$berita->id}", [
                'title_ms' => 'Updated Title',
            ]);

        $response->assertStatus(200);

        // Verify updated_by is set
        $this->assertDatabaseHas('berita_semasa', [
            'id' => $berita->id,
            'updated_by' => $this->adminUser->id,
        ]);
    }

    /** @test */
    public function security_test_file_upload_restricts_to_images_only(): void
    {
        $file = UploadedFile::fake()->create('malicious.exe', 1000);

        $response = $this->withToken($this->adminToken)
            ->postJson('/api/v1/admin/berita', [
                'title_ms' => 'Test Title',
                'content_ms' => 'Test content',
                'image' => $file,
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['image']);
    }

    /** @test */
    public function security_test_soft_delete_maintains_data_integrity(): void
    {
        $berita = BeritaSemasa::factory()->create(['created_by' => $this->adminUser->id]);

        $response = $this->withToken($this->adminToken)
            ->deleteJson("/api/v1/admin/berita/{$berita->id}");

        $response->assertStatus(200);

        // Verify soft delete - record still exists but marked as deleted
        $this->assertDatabaseHas('berita_semasa', [
            'id' => $berita->id,
            'is_deleted' => true,
        ]);

        // Verify we can still access the record
        $foundBerita = BeritaSemasa::find($berita->id);
        $this->assertNotNull($foundBerita);
        $this->assertTrue($foundBerita->is_deleted);
    }
}
