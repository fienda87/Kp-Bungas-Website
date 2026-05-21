<?php

namespace Database\Seeders;

use App\Models\News;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = ['Berita', 'Kegiatan', 'Pengumuman'];

        foreach ($categories as $category) {
            News::create([
                'title' => "Sampel Berita di {$category}",
                'slug' => Str::slug("Sampel Berita di {$category}") . '-' . rand(1000, 9999),
                'category' => $category,
                'content' => "Ini adalah konten sampel untuk berita dalam kategori {$category}.",
                'status' => 'published',
                'published_at' => now(),
            ]);
        }
    }
}
