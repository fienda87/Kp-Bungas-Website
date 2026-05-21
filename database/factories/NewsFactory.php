<?php

namespace Database\Factories;

use App\Models\News;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\News>
 */
class NewsFactory extends Factory
{
    protected $model = News::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence();
        return [
            'title' => $title,
            'slug' => Str::slug($title) . '-' . rand(1000, 9999),
            'category' => $this->faker->randomElement(['Berita', 'Kegiatan', 'Pengumuman']),
            'content' => $this->faker->paragraphs(3, true),
            'thumbnail' => null,
            'status' => 'published',
            'published_at' => now(),
        ];
    }
}
