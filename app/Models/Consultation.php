<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Consultation extends Model
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
        'service',
        'preferred_date',
        'contact_method',
        'priority',
        'details',
        'attachments',
        'status',
        'admin_notes',
        'handled_at',
    ];

    protected function casts(): array
    {
        return [
            'attachments' => 'array',
            'preferred_date' => 'date',
            'handled_at' => 'datetime',
        ];
    }
}
