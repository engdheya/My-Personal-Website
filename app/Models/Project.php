<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'title_ar',
        'slug',
        'short_description',
        'short_description_ar',
        'description',
        'description_ar',
        'problem',
        'problem_ar',
        'solution',
        'solution_ar',
        'main_image',
        'gallery',
        'technologies',
        'category',
        'github_url',
        'live_url',
        'status',
        'project_date',
        'is_featured',
        'features',
        'seo_title',
        'meta_description',
        'og_image',
    ];

    protected $casts = [
        'gallery' => 'array',
        'features' => 'array',
        'is_featured' => 'boolean',
    ];

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
