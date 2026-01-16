<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Administrator',
                'slug' => 'admin',
                'description' => 'Full system access including user management and role management.',
                'is_default' => false,
            ],
            [
                'name' => 'Staff',
                'slug' => 'staff',
                'description' => 'Can manage content but cannot manage users or roles.',
                'is_default' => false,
            ],
            [
                'name' => 'Viewer',
                'slug' => 'viewer',
                'description' => 'Read-only access to content.',
                'is_default' => true,
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
