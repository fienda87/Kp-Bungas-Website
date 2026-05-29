<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

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
        return $this->thumbnail ? Storage::url($this->thumbnail) : null;
    }

    public function getPdfUrlAttribute()
    {
        return $this->pdf_file ? Storage::url($this->pdf_file) : null;
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
