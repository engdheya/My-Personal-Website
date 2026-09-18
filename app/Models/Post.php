<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'title_ar',
        'slug',
        'excerpt',
        'excerpt_ar',
        'content',
        'content_ar',
        'featured_image',
        'alt_text',
        'status',
        'published_at',
        'reading_time',
        'seo_title',
        'meta_description',
        'canonical_url',
        'og_image',
        'focus_keyword',
        'views_count',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'views_count' => 'integer',
        'reading_time' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                     ->where('published_at', '<=', now());
    }

    /**
     * Is this article live (published and not scheduled in the future)?
     */
    public function isPublished(): bool
    {
        return $this->status === 'published'
            && $this->published_at !== null
            && $this->published_at->lessThanOrEqualTo(now());
    }

    /**
     * Estimated reading time in minutes (Arabic and English aware).
     */
    public function readingTime(): int
    {
        $content = post_body($this);
        $words = max(1, count(preg_split('/\s+/u', trim(strip_tags($content)), -1, PREG_SPLIT_NO_EMPTY)));

        return max(1, (int) ceil($words / 200));
    }
}
