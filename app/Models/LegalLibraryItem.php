<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class LegalLibraryItem extends Model
{
    use HasFactory, SoftDeletes;

    public const CATEGORY_LAWS = 'laws';
    public const CATEGORY_JUDICIAL_DECISIONS = 'judicial_decisions';

    public const CATEGORIES = [
        self::CATEGORY_LAWS => 'القوانين والقرارات القانونية',
        self::CATEGORY_JUDICIAL_DECISIONS => 'القرارات القضائية',
    ];

    protected $fillable = [
        'title',
        'slug',
        'category',
        'short_description',
        'content',
        'published_at',
        'pdf_path',
        'is_active',
        'sort_order',
        'seo_title',
        'seo_description',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function getCategoryLabelAttribute(): string
    {
        return self::CATEGORIES[$this->category] ?? $this->category;
    }

    public function getCategoryRouteAttribute(): string
    {
        return $this->category === self::CATEGORY_JUDICIAL_DECISIONS ? 'judicial-decisions' : 'laws';
    }

    public function getPdfUrlAttribute(): ?string
    {
        return $this->pdf_path ? Storage::disk('public')->url($this->pdf_path) : null;
    }
}
