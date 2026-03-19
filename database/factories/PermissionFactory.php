<?php

namespace Database\Factories;

use App\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Permission>
 */
class PermissionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $modules = ['users', 'roles', 'permissions', 'berita', 'kemudahan', 'takwim'];
        $actions = ['view', 'create', 'edit', 'delete', 'manage'];

        return [
            'role_id' => Role::factory(),
            'name' => fake()->randomElement($actions) . '.' . fake()->randomElement($modules),
            'description' => fake()->sentence(),
            'is_active' => true,
        ];
    }

    /**
     * Indicate that the permission is inactive.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }

    /**
     * Create a permission for a specific role.
     */
    public function forRole(int $roleId): static
    {
        return $this->state(fn (array $attributes) => [
            'role_id' => $roleId,
        ]);
    }
}
