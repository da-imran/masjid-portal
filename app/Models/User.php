<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'is_active',
        'is_deleted',
        'is_blocked',
        'blocked_at',
        'blocked_reason',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'is_deleted' => 'boolean',
        'is_blocked' => 'boolean',
        'blocked_at' => 'datetime',
    ];

    /**
     * Get the user's role.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get all permissions for the user through their role.
     */
    public function permissions(): BelongsToMany
    {
        return $this->role()->first()->permissions() ?? new BelongsToMany(
            Permission::query()->getQuery(),
            '',
            '',
            ''
        );
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string|array $roles): bool
    {
        $userRole = $this->role;

        if (!$userRole) {
            return false;
        }

        $roleSlugs = is_array($roles) ? $roles : [$roles];

        return in_array($userRole->slug, $roleSlugs);
    }

    /**
     * Check if user has a specific permission.
     */
    public function hasPermission(string $permissionSlug): bool
    {
        $role = $this->role;

        if (!$role) {
            return false;
        }

        return $role->hasPermission($permissionSlug);
    }

    /**
     * Check if user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if user is staff.
     */
    public function isStaff(): bool
    {
        return $this->hasRole('staff');
    }

    /**
     * Check if user is a viewer.
     */
    public function isViewer(): bool
    {
        return $this->hasRole('viewer');
    }

    /**
     * Block the user.
     */
    public function block(?string $reason = null): bool
    {
        $this->is_blocked = true;
        $this->blocked_at = now();
        $this->blocked_reason = $reason;

        return $this->save();
    }

    /**
     * Unblock the user.
     */
    public function unblock(): bool
    {
        $this->is_blocked = false;
        $this->blocked_at = null;
        $this->blocked_reason = null;

        return $this->save();
    }

    /**
     * Scope to filter active (non-blocked) users.
     */
    public function scopeActive($query)
    {
        return $query->where('is_blocked', false);
    }

    /**
     * Scope to filter blocked users.
     */
    public function scopeBlocked($query)
    {
        return $query->where('is_blocked', true);
    }
}
