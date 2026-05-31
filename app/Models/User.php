<?php

namespace App\Models;

use App\Support\AdminPermissions;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'admin_role_id', 'permissions', 'is_active'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'permissions' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function adminRole(): BelongsTo
    {
        return $this->belongsTo(AdminRole::class);
    }

    public function canAccessAdmin(string $permission): bool
    {
        if (! $this->is_active) {
            return false;
        }

        if ($this->adminRole?->is_active) {
            return $this->adminRole->hasPermission($permission);
        }

        $permissions = $this->permissions ?: AdminPermissions::presetForRole($this->role);

        return in_array('*', $permissions, true) || in_array($permission, $permissions, true);
    }

    public function adminPermissions(): array
    {
        if ($this->adminRole?->is_active) {
            return $this->adminRole->permissions ?: [];
        }

        return $this->permissions ?: AdminPermissions::presetForRole($this->role);
    }
}
