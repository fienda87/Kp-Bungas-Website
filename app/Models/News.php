<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class News extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'news';

    protected $fillable = [
        'title',
        'slug',
        'category',
        'thumbnail',
        'content',
        'status',
        'published_at',
        'pdf_file',
        'view_count',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'view_count' => 'integer',
    ];

    protected $appends = ['thumbnail_url', 'pdf_url', 'safe_content'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function getThumbnailUrlAttribute()
    {
        return $this->publicFileUrl($this->thumbnail);
    }

    public function getPdfUrlAttribute()
    {
        return $this->publicFileUrl($this->pdf_file);
    }

    private function publicFileUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (
            str_starts_with($path, 'http://')
            || str_starts_with($path, 'https://')
            || str_starts_with($path, '/')
        ) {
            return $path;
        }

        if (str_starts_with($path, 'images/')) {
            return '/'.$path;
        }

        return '/storage/'.ltrim($path, '/');
    }

    public function getSafeContentAttribute()
    {
        $content = (string) $this->content;

        $content = preg_replace('/<\s*(script|iframe|object|embed)[^>]*>.*?<\s*\/\s*\1\s*>/is', '', $content);
        $content = preg_replace('/\s+on[a-z]+\s*=\s*(".*?"|\'.*?\'|[^\s>]+)/is', '', $content);
        $content = preg_replace('/\s+href\s*=\s*("|\')\s*javascript:.*?\1/is', ' href="#"', $content);

        return $content;
    }

    public function incrementViewCount()
    {
        $this->increment('view_count');
    }
}
