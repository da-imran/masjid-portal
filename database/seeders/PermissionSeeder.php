<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define modules and their actions
        $modules = [
            'users' => ['view', 'create', 'edit', 'delete', 'block'],
            'berita' => ['view', 'create', 'edit', 'delete', 'publish'],
            'pengumuman' => ['view', 'create', 'edit', 'delete', 'publish'],
            'downloads' => ['view', 'create', 'edit', 'delete'],
            'kemudahan' => ['view', 'create', 'edit', 'delete'],
            'takwim' => ['view', 'create', 'edit', 'delete'],
            'kutipan' => ['view', 'create', 'edit', 'delete'],
        ];

        // Create permissions
        $permissions = [];
        foreach ($modules as $module => $actions) {
            foreach ($actions as $action) {
                $name = ucfirst($module) . ' ' . ucfirst($action);
                $slug = $module . '.' . $action;
                $description = "Permission to $action $module";

                $permission = Permission::create([
                    'name' => $name,
                    'slug' => $slug,
                    'module' => $module,
                    'action' => $action,
                    'description' => $description,
                ]);

                $permissions[] = $permission;
            }
        }

        // Assign permissions to roles
        $adminRole = Role::where('slug', 'admin')->first();
        $staffRole = Role::where('slug', 'staff')->first();
        $viewerRole = Role::where('slug', 'viewer')->first();

        // Admin gets all permissions
        if ($adminRole) {
            foreach ($permissions as $permission) {
                $adminRole->permissions()->attach($permission->id);
            }
        }

        // Staff gets all permissions except user management, roles, and blocking
        $staffPermissions = array_filter($permissions, function ($permission) {
            return !str_starts_with($permission->slug, 'users.') &&
                   !str_contains($permission->slug, 'block');
        });

        if ($staffRole) {
            foreach ($staffPermissions as $permission) {
                $staffRole->permissions()->attach($permission->id);
            }
        }

        // Viewer only gets view permissions
        $viewerPermissions = array_filter($permissions, function ($permission) {
            return str_ends_with($permission->slug, '.view');
        });

        if ($viewerRole) {
            foreach ($viewerPermissions as $permission) {
                $viewerRole->permissions()->attach($permission->id);
            }
        }
    }
}
