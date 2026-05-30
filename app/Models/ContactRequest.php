<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactRequest extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUSES = [
        'pending' => 'قيد الانتظار',
        'following' => 'جاري المتابعة',
        'completed' => 'مكتملة',
    ];

    protected $fillable = [
        'name',
        'phone',
        'email',
        'message',
        'status',
        'admin_notes',
        'handled_at',
    ];

    protected function casts(): array
    {
        return ['handled_at' => 'datetime'];
    }
}
