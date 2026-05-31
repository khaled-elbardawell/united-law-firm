<?php

namespace App\Models;

use App\Support\AdminPermissions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdminRole extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'permissions',
        'is_active',
        'is_system',
    ];

    protected function casts(): array
    {
        return [
            'permissions' => 'array',
            'is_active' => 'boolean',
            'is_system' => 'boolean',
        ];
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function hasPermission(string $permission): bool
    {
        $permissions = $this->permissions ?: [];

        return in_array('*', $permissions, true) || in_array($permission, $permissions, true);
    }

    public function permissionCountLabel(): string
    {
        $permissions = $this->permissions ?: [];

        return in_array('*', $permissions, true) ? 'كل الصلاحيات' : count(AdminPermissions::sanitize($permissions)).' صلاحية';
    }
}
