<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kemudahan>
 */
class KemudahanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title_ms' => 'Kemudahan ' . fake()->word(),
            'title_en' => fake()->word() . ' Facility',
            'description_ms' => fake()->paragraph(),
            'description_en' => fake()->paragraph(),
            'image_name' => fake()->word() . '.jpg',
            'order_column' => fake()->numberBetween(1, 100),
            'is_active' => true,
            'is_deleted' => false,
            'created_by' => User::factory(),
            'updated_by' => null,
        ];
    }

    /**
     * Indicate that the facility is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the facility is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the facility is deleted (soft delete).
     */
    public function deleted(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_deleted' => true,
        ]);
    }
}
