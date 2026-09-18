<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_ar',
        'slug',
        'description',
        'description_ar',
        'image',
        'seo_title',
        'meta_description',
    ];

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
