<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lawyer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
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
}
