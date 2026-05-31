<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gallery extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'album_name',
        'description',
        'cover_image',
    ];

    protected $appends = ['cover_image_url'];

    public function photos(): HasMany
    {
        return $this->hasMany(GalleryPhoto::class);
    }

    public function getCoverImageUrlAttribute()
    {
        return $this->cover_image ? '/storage/'.ltrim($this->cover_image, '/') : null;
    }
}
