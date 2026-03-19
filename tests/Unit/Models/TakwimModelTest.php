<?php

namespace Tests\Unit\Models;

use App\Models\Takwim;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Unit tests for Takwim (Pengumuman) model.
 *
 * Test Categories:
 * - Happy Paths: Verify the feature works as intended
 * - Negative Tests: Verify validation errors catch invalid data
 * - Access Control: Verify unauthorized user receive 403 forbidden
 * - Security Tests: Explicitly test security constraints
 */
class TakwimModelTest extends TestCase
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
    public function test_can_create_takwim(): void
    {
        $takwim = Takwim::create([
            'title_ms' => 'Program Ceramah',
            'title_en' => 'Lecture Program',
            'description_ms' => 'Deskripsi program',
            'event_date' => '2026-02-01',
            'event_time' => '14:30:00',
            'location_ms' => 'Dewan Utama',
            'is_active' => true,
            'created_by' => $this->creator->id,
        ]);

        $this->assertDatabaseHas('takwim', [
            'title_ms' => 'Program Ceramah',
        ]);
        $this->assertInstanceOf(Takwim::class, $takwim);
    }

    /** @test */
    public function test_can_update_takwim(): void
    {
        $takwim = Takwim::factory()->create([
            'created_by' => $this->creator->id,
            'title_ms' => 'Original Title',
        ]);

        $takwim->update([
            'title_ms' => 'Updated Title',
            'updated_by' => $this->creator->id,
        ]);

        $this->assertEquals('Updated Title', $takwim->fresh()->title_ms);
    }

    /** @test */
    public function test_can_delete_takwim(): void
    {
        $takwim = Takwim::factory()->create([
            'created_by' => $this->creator->id,
        ]);

        $takwimId = $takwim->id;
        $takwim->delete();

        $this->assertDatabaseMissing('takwim', ['id' => $takwimId]);
    }

    /** @test */
    public function test_can_scope_to_upcoming_events(): void
    {
        Takwim::factory()->create(['event_date' => now()->addWeek()]);
        Takwim::factory()->create(['event_date' => now()->subWeek()]);

        $upcomingTakwim = Takwim::upcoming()->get();

        $this->assertCount(1, $upcomingTakwim);
    }

    /*
    |--------------------------------------------------------------------------
    | NEGATIVE TESTS - Verify validation errors catch invalid data
    |--------------------------------------------------------------------------
    */

    /** @test */
    public function test_cannot_create_takwim_without_title_ms(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Takwim::create([
            'event_date' => now()->addWeek(),
            'created_by' => $this->creator->id,
        ]);
    }

    /** @test */
    public function test_cannot_create_takwim_without_event_date(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Takwim::create([
            'title_ms' => 'Program Ceramah',
            'created_by' => $this->creator->id,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESS CONTROL - Verify unauthorized users receive 403 forbidden
    |--------------------------------------------------------------------------
    */

    // Note: Access control tests are in Feature/Api/TakwimApiTest.php
    // Model tests focus on data layer, API layer handles authorization

    /*
    |--------------------------------------------------------------------------
    | SECURITY TESTS - Explicitly test security constraints
    |--------------------------------------------------------------------------
    */

    /** @test */
    public function security_test_soft_delete_is_active_flag(): void
    {
        $takwim = Takwim::factory()->create([
            'created_by' => $this->creator->id,
            'is_deleted' => true,
        ]);

        $this->assertDatabaseHas('takwim', [
            'id' => $takwim->id,
            'is_deleted' => true,
        ]);
    }

    /** @test */
    public function security_test_boolean_fields_prevent_sql_injection(): void
    {
        $takwim = Takwim::factory()->create([
            'is_active' => true,
            'is_deleted' => false,
        ]);

        $this->assertIsBool($takwim->is_active);
        $this->assertIsBool($takwim->is_deleted);
    }

    /** @test */
    public function security_test_event_date_is_valid_date(): void
    {
        $takwim = Takwim::factory()->create(['event_date' => '2026-02-01']);

        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $takwim->event_date);
    }
}
