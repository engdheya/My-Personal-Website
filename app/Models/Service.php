<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'title_ar',
        'slug',
        'icon',
        'short_description',
        'short_description_ar',
        'description',
        'description_ar',
        'features',
        'sort_order',
    ];

    protected $casts = [
        'features' => 'array',
        'sort_order' => 'integer',
    ];
}
