<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'description'];

    /**
     * Get the news items for the category.
     * Note: News uses category name as string since the migration.
     */
    public function news(): HasMany
    {
        return $this->hasMany(News::class, 'category', 'name');
    }
}
