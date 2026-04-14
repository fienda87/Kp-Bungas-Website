<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ArticleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first() ?? User::factory()->create();
        $categories = Category::all();

        if ($categories->isEmpty()) {
            $categories = collect([Category::create(['name' => 'General', 'slug' => 'general'])]);
        }

        foreach ($categories as $category) {
            Article::create([
                'user_id' => $user->id,
                'category_id' => $category->id,
                'title' => "Sample Article in {$category->name}",
                'slug' => Str::slug("Sample Article in {$category->name}"),
                'content' => "This is the content for a sample article in the {$category->name} category.",
                'excerpt' => "Short excerpt for {$category->name} article.",
                'status' => 'published',
                'published_at' => now(),
            ]);
        }
    }
}
