<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create roles
        $adminRole = Role::firstOrCreate(
            ['name' => 'Admin'],
            [
                'description' => 'Administrator with full system access',
                'is_active' => true,
            ]
        );

        $staffRole = Role::firstOrCreate(
            ['name' => 'Staff'],
            [
                'description' => 'Staff member with content management access',
                'is_active' => true,
            ]
        );

        $viewerRole = Role::firstOrCreate(
            ['name' => 'Viewer'],
            [
                'description' => 'Viewer with read-only access',
                'is_active' => true,
            ]
        );

        // Admin Permissions - Full access to all modules
        $adminPermissions = [
            // Admin Settings
            ['name' => 'admin.settings.view', 'description' => 'View admin settings'],
            ['name' => 'admin.settings.create', 'description' => 'Create admin settings'],
            ['name' => 'admin.settings.edit', 'description' => 'Edit admin settings'],
            ['name' => 'admin.settings.delete', 'description' => 'Delete admin settings'],

            // Users
            ['name' => 'users.view', 'description' => 'View users'],
            ['name' => 'users.create', 'description' => 'Create users'],
            ['name' => 'users.edit', 'description' => 'Edit users'],
            ['name' => 'users.delete', 'description' => 'Delete users'],

            // Roles
            ['name' => 'roles.view', 'description' => 'View roles'],
            ['name' => 'roles.create', 'description' => 'Create roles'],
            ['name' => 'roles.edit', 'description' => 'Edit roles'],
            ['name' => 'roles.delete', 'description' => 'Delete roles'],

            // Berita
            ['name' => 'berita.view', 'description' => 'View berita'],
            ['name' => 'berita.create', 'description' => 'Create berita'],
            ['name' => 'berita.edit', 'description' => 'Edit berita'],
            ['name' => 'berita.delete', 'description' => 'Delete berita'],

            // Takwim
            ['name' => 'takwim.view', 'description' => 'View takwim'],
            ['name' => 'takwim.create', 'description' => 'Create takwim'],
            ['name' => 'takwim.edit', 'description' => 'Edit takwim'],
            ['name' => 'takwim.delete', 'description' => 'Delete takwim'],

            // Kemudahan
            ['name' => 'kemudahan.view', 'description' => 'View kemudahan'],
            ['name' => 'kemudahan.create', 'description' => 'Create kemudahan'],
            ['name' => 'kemudahan.edit', 'description' => 'Edit kemudahan'],
            ['name' => 'kemudahan.delete', 'description' => 'Delete kemudahan'],
        ];

        foreach ($adminPermissions as $permission) {
            Permission::firstOrCreate(
                [
                    'role_id' => $adminRole->id,
                    'name' => $permission['name'],
                ],
                [
                    'description' => $permission['description'],
                    'is_active' => true,
                ]
            );
        }

        // Staff Permissions - Content management only (Berita, Takwim, Kemudahan)
        $staffPermissions = [
            // Berita
            ['name' => 'berita.view', 'description' => 'View berita'],
            ['name' => 'berita.create', 'description' => 'Create berita'],
            ['name' => 'berita.edit', 'description' => 'Edit berita'],
            ['name' => 'berita.delete', 'description' => 'Delete berita'],

            // Takwim
            ['name' => 'takwim.view', 'description' => 'View takwim'],
            ['name' => 'takwim.create', 'description' => 'Create takwim'],
            ['name' => 'takwim.edit', 'description' => 'Edit takwim'],
            ['name' => 'takwim.delete', 'description' => 'Delete takwim'],

            // Kemudahan
            ['name' => 'kemudahan.view', 'description' => 'View kemudahan'],
            ['name' => 'kemudahan.create', 'description' => 'Create kemudahan'],
            ['name' => 'kemudahan.edit', 'description' => 'Edit kemudahan'],
            ['name' => 'kemudahan.delete', 'description' => 'Delete kemudahan'],
        ];

        foreach ($staffPermissions as $permission) {
            Permission::firstOrCreate(
                [
                    'role_id' => $staffRole->id,
                    'name' => $permission['name'],
                ],
                [
                    'description' => $permission['description'],
                    'is_active' => true,
                ]
            );
        }

        // Viewer Permissions - View only
        $viewerPermissions = [
            ['name' => 'berita.view', 'description' => 'View berita'],
            ['name' => 'takwim.view', 'description' => 'View takwim'],
            ['name' => 'kemudahan.view', 'description' => 'View kemudahan'],
        ];

        foreach ($viewerPermissions as $permission) {
            Permission::firstOrCreate(
                [
                    'role_id' => $viewerRole->id,
                    'name' => $permission['name'],
                ],
                [
                    'description' => $permission['description'],
                    'is_active' => true,
                ]
            );
        }

        $this->command->info('Roles and permissions seeded successfully.');
        $this->command->info('Admin role: ' . $adminRole->permissions()->count() . ' permissions');
        $this->command->info('Staff role: ' . $staffRole->permissions()->count() . ' permissions');
        $this->command->info('Viewer role: ' . $viewerRole->permissions()->count() . ' permissions');

        // Create default admin user
        $adminUser = User::firstOrCreate(
            ['email' => 'ehsan@masjid.test'],
            [
                'name' => 'Ehsan',
                'password' => bcrypt('ehsan123'),
                'role_id' => $adminRole->id,
                'is_active' => true,
                'is_blocked' => false,
            ]
        );

        $this->command->info('Admin user created: ' . $adminUser->email);
    }
}
