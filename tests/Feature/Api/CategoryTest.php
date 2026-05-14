<?php

namespace Tests\Feature\Api;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test listing categories.
     */
    public function test_can_list_categories(): void
    {
        Category::factory()->count(5)->create();

        $response = $this->getJson('/api/categories');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'slug',
                    'description'
                ]
            ]
        ]);
        $response->assertJsonCount(5, 'data');
    }

    /**
     * Test showing a single category.
     */
    public function test_can_show_category_details(): void
    {
        $category = Category::factory()->create();

        $response = $this->getJson("/api/categories/{$category->slug}");

        $response->assertStatus(200);
        $response->assertJsonPath('data.name', $category->name);
    }
}
