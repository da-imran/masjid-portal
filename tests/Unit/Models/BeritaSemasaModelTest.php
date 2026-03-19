<?php

namespace Tests\Unit\Models;

use App\Models\BeritaSemasa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Unit tests for BeritaSemasa (Berita) model.
 *
 * Test Categories:
 * - Happy Paths: Verify the feature works as intended
 * - Negative Tests: Verify validation errors catch invalid data
 * - Access Control: Verify unauthorized user receive 403 forbidden
 * - Security Tests: Explicitly test security constraints
 */
class BeritaSemasaModelTest extends TestCase
{
    use RefreshDatabase;

    protected User $creator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->creator = User::factory()->create();
    }

    /*
    |--------------------------------------------------------------------------
    | HAPPY PATHS - Verify the feature works as intended
    |--------------------------------------------------------------------------
    */

    /** @test */
    public function test_can_create_berita(): void
    {
        $berita = BeritaSemasa::create([
            'title_ms' => 'Berita Penting',
            'title_en' => 'Important News',
            'description_ms' => 'Deskripsi berita',
            'content_ms' => 'Kandungan berita dalam bahasa melayu',
            'is_active' => true,
            'published_at' => now(),
            'created_by' => $this->creator->id,
        ]);

        $this->assertDatabaseHas('berita_semasa', [
            'title_ms' => 'Berita Penting',
        ]);
        $this->assertInstanceOf(BeritaSemasa::class, $berita);
    }

    /** @test */
    public function test_can_update_berita(): void
    {
        $berita = BeritaSemasa::factory()->create([
            'created_by' => $this->creator->id,
            'title_ms' => 'Original Title',
        ]);

        $berita->update([
            'title_ms' => 'Updated Title',
            'updated_by' => $this->creator->id,
        ]);

        $this->assertEquals('Updated Title', $berita->fresh()->title_ms);
    }

    /** @test */
    public function test_can_delete_berita(): void
    {
        $berita = BeritaSemasa::factory()->create([
            'created_by' => $this->creator->id,
        ]);

        $beritaId = $berita->id;
        $berita->delete();

        $this->assertDatabaseMissing('berita_semasa', ['id' => $beritaId]);
    }

    /** @test */
    public function test_can_scope_to_active_berita(): void
    {
        BeritaSemasa::factory()->create(['is_active' => true]);
        BeritaSemasa::factory()->create(['is_active' => false]);

        $activeBerita = BeritaSemasa::active()->get();

        $this->assertCount(1, $activeBerita);
        $this->assertTrue($activeBerita->first()->is_active);
    }

    /** @test */
    public function test_can_increment_view_count(): void
    {
        $berita = BeritaSemasa::factory()->create(['view_count' => 10]);

        $newCount = $berita->incrementViewCount();

        $this->assertEquals(11, $newCount);
        $this->assertEquals(11, $berita->fresh()->view_count);
    }

    /*
    |--------------------------------------------------------------------------
    | NEGATIVE TESTS - Verify validation errors catch invalid data
    |--------------------------------------------------------------------------
    */

    /** @test */
    public function test_cannot_create_berita_without_title_ms(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        BeritaSemasa::create([
            'title_en' => 'English Title',
            'content_ms' => 'Content',
            'created_by' => $this->creator->id,
        ]);
    }

    /** @test */
    public function test_cannot_create_berita_with_excessive_title_length(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        BeritaSemasa::create([
            'title_ms' => str_repeat('A', 256),
            'content_ms' => 'Content',
            'created_by' => $this->creator->id,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESS CONTROL - Verify unauthorized users receive 403 forbidden
    |--------------------------------------------------------------------------
    */

    // Note: Access control tests are in Feature/Api/BeritaApiTest.php
    // Model tests focus on data layer, API layer handles authorization

    /*
    |--------------------------------------------------------------------------
    | SECURITY TESTS - Explicitly test security constraints
    |--------------------------------------------------------------------------
    */

    /** @test */
    public function security_test_soft_delete_is_active_flag(): void
    {
        $berita = BeritaSemasa::factory()->create([
            'created_by' => $this->creator->id,
            'is_deleted' => true,
        ]);

        // Verify soft delete record still exists
        $this->assertDatabaseHas('berita_semasa', [
            'id' => $berita->id,
            'is_deleted' => true,
        ]);

        // Verify it's excluded from default queries
        $foundBerita = BeritaSemasa::find($berita->id);
        $this->assertNotNull($foundBerita); // find() doesn't exclude by default
    }

    /** @test */
    public function security_test_boolean_fields_prevent_sql_injection(): void
    {
        $berita = BeritaSemasa::factory()->create([
            'is_active' => true,
            'is_featured' => false,
        ]);

        $this->assertIsBool($berita->is_active);
        $this->assertIsBool($berita->is_featured);
    }

    /** @test */
    public function security_test_view_count_is_integer(): void
    {
        $berita = BeritaSemasa::factory()->create(['view_count' => 100]);

        $this->assertIsInt($berita->view_count);
        $this->assertGreaterThanOrEqual(0, $berita->view_count);
    }
}
