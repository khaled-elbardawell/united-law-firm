<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lawyer extends Model
{
    use HasFactory, SoftDeletes;

    public const TEAM_FOUNDERS = 'founders';
    public const TEAM_ADMINISTRATION = 'administration';
    public const TEAM_LAWYERS = 'lawyers';
    public const TEAM_TRAINEES = 'trainees';

    public const TEAM_CATEGORIES = [
        self::TEAM_FOUNDERS => 'فريق المؤسسين',
        self::TEAM_ADMINISTRATION => 'فريق الإداريين',
        self::TEAM_LAWYERS => 'فريق المحامين',
        self::TEAM_TRAINEES => 'فريق المتدربين',
    ];

    protected $fillable = [
        'name',
        'slug',
        'team_category',
        'position',
        'specialty',
        'bio',
        'professional_summary',
        'bar_number',
        'years_experience',
        'education',
        'experience',
        'certifications',
        'languages',
        'memberships',
        'awards',
        'court_admissions',
        'tags',
        'email',
        'phone',
        'photo',
        'linkedin_url',
        'website_url',
        'cv_file',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'tags' => 'array',
            'education' => 'array',
            'experience' => 'array',
            'certifications' => 'array',
            'languages' => 'array',
            'memberships' => 'array',
            'awards' => 'array',
            'court_admissions' => 'array',
            'is_active' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function getTeamCategoryLabelAttribute(): ?string
    {
        return self::TEAM_CATEGORIES[$this->team_category] ?? null;
    }
}
