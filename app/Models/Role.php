<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the users that belong to this role.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'role_user');
    }

    /**
     * Get the permissions for this role.
     */
    public function permissions(): HasMany
    {
        return $this->hasMany(Permission::class);
    }

    /**
     * Scope to filter active roles.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by name.
     */
    public function scopeByName($query, string $name)
    {
        return $query->where('name', $name);
    }

    /**
     * Check if role has a specific permission by name.
     */
    public function hasPermission(string $permissionName): bool
    {
        return $this->permissions()->where('name', $permissionName)->where('is_active', true)->exists();
    }

    /**
     * Grant a permission to this role.
     */
    public function givePermissionTo(Permission|string $permission, string $description = null): self
    {
        if (is_string($permission)) {
            // Check if permission already exists for this role
            $existing = $this->permissions()->where('name', $permission)->first();
            if ($existing) {
                return $this;
            }

            $permission = new Permission([
                'name' => $permission,
                'description' => $description ?? $permission,
                'is_active' => true,
            ]);
        }

        $this->permissions()->save($permission);

        return $this;
    }

    /**
     * Revoke a permission from this role.
     */
    public function revokePermissionTo(Permission|string $permission): self
    {
        if (is_string($permission)) {
            $permission = $this->permissions()->where('name', $permission)->first();
        }

        if ($permission) {
            $permission->delete();
        }

        return $this;
    }
}
