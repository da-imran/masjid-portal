<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Role>
 */
class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = fake()->unique()->words(2, true);

        return [
            'name' => ucwords($name),
            'description' => fake()->sentence(),
            'is_active' => true,
        ];
    }

    /**
     * Indicate that the role is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Create an admin role.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Administrator',
            'description' => 'Full system access',
            'is_active' => true,
        ]);
    }

    /**
     * Create a staff role.
     */
    public function staff(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Staff',
            'description' => 'Staff access',
            'is_active' => true,
        ]);
    }

    /**
     * Create a viewer role.
     */
    public function viewer(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => 'Viewer',
            'description' => 'Read-only access',
            'is_active' => true,
        ]);
    }
}
