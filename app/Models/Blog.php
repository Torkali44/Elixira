<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Blog extends Model
{
    protected $fillable = [
        'title_en',
        'title_ar',
        'slug',
        'content_en',
        'content_ar',
        'summary_en',
        'summary_ar',
        'image',
        'video_url',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    /**
     * Get the title based on current locale.
     */
    public function getTitleAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->title_ar : $this->title_en;
    }

    /**
     * Get the content based on current locale.
     */
    public function getContentAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->content_ar : $this->content_en;
    }

    /**
     * Get the summary based on current locale or fallback to content limit.
     */
    public function getSummaryAttribute(): string
    {
        if (app()->getLocale() === 'ar') {
            return $this->summary_ar ?: Str::limit(strip_tags($this->content_ar), 150);
        }

        return $this->summary_en ?: Str::limit(strip_tags($this->content_en), 150);
    }

    public function images()
    {
        return $this->hasMany(BlogImage::class)->orderBy('sort_order');
    }

    /**
     * Get the embed video URL from standard youtube/vimeo link.
     */
    public function getEmbedVideoUrlAttribute(): ?string
    {
        if (! $this->video_url) {
            return null;
        }

        // YouTube
        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_\-]{11})/', $this->video_url, $m)) {
            return 'https://www.youtube.com/embed/'.$m[1];
        }

        // Vimeo
        if (preg_match('/(?:vimeo\.com\/|player\.vimeo\.com\/video\/)(\d+)/', $this->video_url, $m)) {
            return 'https://player.vimeo.com/video/'.$m[1];
        }

        return null;
    }
}
