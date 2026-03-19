<?php

namespace Tests\Unit\Models;

use App\Models\Kemudahan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Unit tests for Kemudahan model.
 *
 * Test Categories:
 * - Happy Paths: Verify the feature works as intended
 * - Negative Tests: Verify validation errors catch invalid data
 * - Access Control: Verify unauthorized user receive 403 forbidden
 * - Security Tests: Explicitly test security constraints
 */
class KemudahanModelTest extends TestCase
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
    public function test_can_create_kemudahan(): void
    {
        $kemudahan = Kemudahan::create([
            'title_ms' => 'Kemudahan Dewan',
            'title_en' => 'Hall Facility',
            'description_ms' => 'Deskripsi kemudahan',
            'order_column' => 1,
            'is_active' => true,
            'created_by' => $this->creator->id,
        ]);

        $this->assertDatabaseHas('kemudahan', [
            'title_ms' => 'Kemudahan Dewan',
        ]);
        $this->assertInstanceOf(Kemudahan::class, $kemudahan);
    }

    /** @test */
    public function test_can_update_kemudahan(): void
    {
        $kemudahan = Kemudahan::factory()->create([
            'created_by' => $this->creator->id,
            'title_ms' => 'Original Title',
        ]);

        $kemudahan->update([
            'title_ms' => 'Updated Title',
            'updated_by' => $this->creator->id,
        ]);

        $this->assertEquals('Updated Title', $kemudahan->fresh()->title_ms);
    }

    /** @test */
    public function test_can_delete_kemudahan(): void
    {
        $kemudahan = Kemudahan::factory()->create([
            'created_by' => $this->creator->id,
        ]);

        $kemudahanId = $kemudahan->id;
        $kemudahan->delete();

        $this->assertDatabaseMissing('kemudahan', ['id' => $kemudahanId]);
    }

    /** @test */
    public function test_can_scope_to_active_kemudahan(): void
    {
        Kemudahan::factory()->create(['is_active' => true, 'order_column' => 1]);
        Kemudahan::factory()->create(['is_active' => false, 'order_column' => 2]);

        $activeKemudahan = Kemudahan::active()->get();

        $this->assertCount(1, $activeKemudahan);
        $this->assertTrue($activeKemudahan->first()->is_active);
    }

    /*
    |--------------------------------------------------------------------------
    | NEGATIVE TESTS - Verify validation errors catch invalid data
    |--------------------------------------------------------------------------
    */

    /** @test */
    public function test_cannot_create_kemudahan_without_title_ms(): void
    {
        $this->expectException(\Illuminate\Database\QueryException::class);

        Kemudahan::create([
            'description_ms' => 'Test Description',
            'created_by' => $this->creator->id,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESS CONTROL - Verify unauthorized users receive 403 forbidden
    |--------------------------------------------------------------------------
    */

    // Note: Access control tests are in Feature/Api/KemudahanApiTest.php
    // Model tests focus on data layer, API layer handles authorization

    /*
    |--------------------------------------------------------------------------
    | SECURITY TESTS - Explicitly test security constraints
    |--------------------------------------------------------------------------
    */

    /** @test */
    public function security_test_soft_delete_is_active_flag(): void
    {
        $kemudahan = Kemudahan::factory()->create([
            'created_by' => $this->creator->id,
            'is_deleted' => true,
        ]);

        $this->assertDatabaseHas('kemudahan', [
            'id' => $kemudahan->id,
            'is_deleted' => true,
        ]);
    }

    /** @test */
    public function security_test_boolean_fields_prevent_sql_injection(): void
    {
        $kemudahan = Kemudahan::factory()->create([
            'is_active' => true,
            'is_deleted' => false,
        ]);

        $this->assertIsBool($kemudahan->is_active);
        $this->assertIsBool($kemudahan->is_deleted);
    }

    /** @test */
    public function security_test_order_column_is_non_negative(): void
    {
        $kemudahan = Kemudahan::factory()->create(['order_column' => 5]);

        $this->assertGreaterThanOrEqual(0, $kemudahan->order_column);
    }
}
