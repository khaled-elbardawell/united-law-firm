<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SeoSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'page_key',
        'page_name',
        'title',
        'description',
        'keywords',
        'canonical_url',
        'og_image',
        'is_indexable',
    ];

    protected function casts(): array
    {
        return ['is_indexable' => 'boolean'];
    }
}
