<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainingRegistration extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUSES = [
        'registered' => 'مسجل',
        'confirmed' => 'مؤكد',
        'cancelled' => 'ملغي',
        'completed' => 'مكتمل',
    ];

    protected $fillable = [
        'training_course_id',
        'name',
        'phone',
        'email',
        'profession',
        'notes',
        'status',
        'admin_notes',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(TrainingCourse::class, 'training_course_id');
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(TrainingAttendance::class);
    }

    public function attendanceProgress(): int
    {
        $days = max(1, (int) ($this->course?->course_days ?? 1));
        $present = $this->attendances->where('status', 'present')->count();

        return (int) round(($present / $days) * 100);
    }
}
