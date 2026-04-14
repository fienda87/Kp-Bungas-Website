<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Technology', 'slug' => 'technology', 'description' => 'All about technology'],
            ['name' => 'Lifestyle', 'slug' => 'lifestyle', 'description' => 'All about lifestyle'],
            ['name' => 'Travel', 'slug' => 'travel', 'description' => 'All about travel'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
