<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Takwim>
 */
class TakwimFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $eventDate = fake()->dateTimeBetween('now', '+3 months');

        return [
            'title_ms' => 'Program ' . fake()->word(),
            'title_en' => fake()->word() . ' Program',
            'description_ms' => fake()->paragraph(),
            'description_en' => fake()->paragraph(),
            'event_date' => $eventDate->format('Y-m-d'),
            'event_time' => fake()->dateTimeBetween('08:00', '22:00')->format('H:i:s'),
            'location_ms' => fake()->city() . ', ' . fake()->state(),
            'location_en' => fake()->city() . ', ' . fake()->state(),
            'image_name' => fake()->word() . '.jpg',
            'is_active' => true,
            'is_deleted' => false,
            'created_by' => User::factory(),
            'updated_by' => null,
        ];
    }

    /**
     * Indicate that the event is active.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => true,
        ]);
    }

    /**
     * Indicate that the event is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Indicate that the event is upcoming.
     */
    public function upcoming(): static
    {
        return $this->state(fn (array $attributes) => [
            'event_date' => fake()->dateTimeBetween('now', '+3 months')->format('Y-m-d'),
        ]);
    }

    /**
     * Indicate that the event is in the past.
     */
    public function past(): static
    {
        return $this->state(fn (array $attributes) => [
            'event_date' => fake()->dateTimeBetween('-3 months', 'now')->format('Y-m-d'),
        ]);
    }

    /**
     * Indicate that the event is deleted (soft delete).
     */
    public function deleted(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_deleted' => true,
        ]);
    }
}
