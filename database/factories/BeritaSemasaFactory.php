<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BeritaSemasa>
 */
class BeritaSemasaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title_ms' => fake()->sentence() . ' (BM)',
            'title_en' => fake()->sentence() . ' (EN)',
            'description_ms' => fake()->paragraph(),
            'description_en' => fake()->paragraph(),
            'content_ms' => fake()->paragraphs(3, true),
            'content_en' => fake()->paragraphs(3, true),
            'image_name' => fake()->word() . '.jpg',
            'view_count' => fake()->numberBetween(0, 1000),
            'is_active' => true,
            'is_deleted' => false,
            'is_featured' => fake()->boolean(20), // 20% chance of being featured
            'published_at' => fake()->dateTimeBetween('-1 month', 'now'),
            'created_by' => User::factory(),
            'updated_by' => null,
        ];
    }

    /**
     * Indicate that the news is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the news is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the news is featured.
     */
    public function featured(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_featured' => true,
        ]);
    }

    /**
     * Indicate that the news is deleted (soft delete).
     */
    public function deleted(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_deleted' => true,
        ]);
    }
}
