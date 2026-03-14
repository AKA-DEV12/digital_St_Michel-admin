<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class BlogPost extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'featured_image',
        'url_video',
        'user_id',
        'blog_category_id',
        'status',
        'is_featured',
        'is_popular',
        'published_at'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'is_featured' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($post) {
            if (!$post->slug) {
                $post->slug = Str::slug($post->title);
            }
        });
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(BlogCategory::class, 'blog_category_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(BlogTag::class, 'blog_post_tag', 'blog_post_id', 'blog_tag_id');
    }

    public function scopePopular($query)
    {
        return $query->where('is_popular', true);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')->whereNotNull('published_at');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function getEmbedUrlAttribute()
    {
        if (!$this->url_video) return null;
        
        if (str_contains($this->url_video, 'youtube.com') || str_contains($this->url_video, 'youtu.be')) {
            if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $this->url_video, $match)) {
                return "https://www.youtube.com/embed/{$match[1]}";
            }
        } elseif (str_contains($this->url_video, 'vimeo.com')) {
            if (preg_match('/vimeo\.com\/([0-9]+)/', $this->url_video, $match)) {
                return "https://player.vimeo.com/video/{$match[1]}";
            }
        }
        
        return $this->url_video;
    }

    public function getVideoThumbnailAttribute()
    {
        if (!$this->url_video) return null;

        if (str_contains($this->url_video, 'youtube.com') || str_contains($this->url_video, 'youtu.be')) {
            if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $this->url_video, $match)) {
                return "https://img.youtube.com/vi/{$match[1]}/hqdefault.jpg";
            }
        } elseif (str_contains($this->url_video, 'vimeo.com')) {
            if (preg_match('/vimeo\.com\/([0-9]+)/', $this->url_video, $match)) {
                // Pour Vimeo, l'API est nécessaire pour récupérer le thumbnail dynamiquement, 
                // mais on peut utiliser une URL de repli ou documenter cette limitation.
                return "https://vumbnail.com/{$match[1]}.jpg";
            }
        }

        return null;
    }
}
