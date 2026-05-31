<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingAttendance extends Model
{
    use HasFactory;

    public const STATUSES = [
        'present' => 'حاضر',
        'absent' => 'غائب',
    ];

    protected $fillable = [
        'training_registration_id',
        'day_number',
        'attendance_date',
        'status',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'attendance_date' => 'date',
        ];
    }

    public function registration(): BelongsTo
    {
        return $this->belongsTo(TrainingRegistration::class, 'training_registration_id');
    }
}
