<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainingCourse extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'trainer_name',
        'location',
        'capacity',
        'course_days',
        'price',
        'course_starts_at',
        'course_ends_at',
        'registration_starts_at',
        'registration_ends_at',
        'hero_image',
        'summary',
        'description',
        'outcomes',
        'requirements',
        'schedule_notes',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'capacity' => 'integer',
            'course_days' => 'integer',
            'price' => 'decimal:2',
            'course_starts_at' => 'date',
            'course_ends_at' => 'date',
            'registration_starts_at' => 'datetime',
            'registration_ends_at' => 'datetime',
            'outcomes' => 'array',
            'requirements' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(TrainingRegistration::class);
    }

    public function activeRegistrations(): HasMany
    {
        return $this->registrations()->where('status', '!=', 'cancelled');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOpenForRegistration(Builder $query): Builder
    {
        return $query->published()
            ->where(function (Builder $query) {
                $query->whereNull('registration_starts_at')->orWhere('registration_starts_at', '<=', now());
            })
            ->where(function (Builder $query) {
                $query->whereNull('registration_ends_at')->orWhere('registration_ends_at', '>=', now());
            });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function isRegistrationOpen(): bool
    {
        if (! $this->is_active) {
            return false;
        }

        $starts = $this->registration_starts_at;
        $ends = $this->registration_ends_at;

        return (! $starts || $starts->lte(now())) && (! $ends || $ends->gte(now())) && ! $this->isFull();
    }

    public function isFull(): bool
    {
        return $this->capacity !== null
            && $this->active_registrations_count !== null
            && $this->active_registrations_count >= $this->capacity;
    }
}
