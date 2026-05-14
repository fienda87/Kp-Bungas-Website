<?php

namespace Tests\Feature\Api;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test listing published articles.
     */
    public function test_can_list_published_articles(): void
    {
        // Create published articles
        Article::factory()->count(3)->create(['status' => 'published']);
        // Create a draft article
        Article::factory()->create(['status' => 'draft']);

        $response = $this->getJson('/api/articles');

        // If the backend engineer hasn't implemented it yet, this will fail.
        // But this is the expected behavior according to the task.
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'slug',
                    'content',
                    'status',
                    'category' => [
                        'id',
                        'name',
                        'slug'
                    ]
                ]
            ]
        ]);
        $response->assertJsonCount(3, 'data');
    }

    /**
     * Test filtering articles by category.
     */
    public function test_can_filter_articles_by_category(): void
    {
        $category = Category::factory()->create();
        Article::factory()->count(2)->create([
            'category_id' => $category->id,
            'status' => 'published'
        ]);
        Article::factory()->create(['status' => 'published']);

        $response = $this->getJson("/api/articles?category={$category->slug}");

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
    }

    /**
     * Test showing a single published article.
     */
    public function test_can_show_published_article_details(): void
    {
        $article = Article::factory()->create(['status' => 'published']);

        $response = $this->getJson("/api/articles/{$article->slug}");

        $response->assertStatus(200);
        $response->assertJsonPath('data.title', $article->title);
    }

    /**
     * Test that draft articles are not found.
     */
    public function test_cannot_see_draft_article_details(): void
    {
        $article = Article::factory()->create(['status' => 'draft']);

        $response = $this->getJson("/api/articles/{$article->slug}");

        $response->assertStatus(404);
    }
}
