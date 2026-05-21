<?php

namespace Tests\Feature\Api;

use App\Models\News;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test listing published news.
     */
    public function test_can_list_published_news(): void
    {
        // Create published news
        News::factory()->count(3)->create(['status' => 'published']);
        // Create a draft news
        News::factory()->create(['status' => 'draft']);

        $response = $this->getJson('/api/news');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'slug',
                    'category',
                    'content',
                    'status',
                    'thumbnail',
                    'published_at',
                    'created_at',
                    'updated_at'
                ]
            ]
        ]);
        $response->assertJsonCount(3, 'data');
    }

    /**
     * Test filtering news by category.
     */
    public function test_can_filter_news_by_category(): void
    {
        News::factory()->count(2)->create([
            'category' => 'Berita',
            'status' => 'published'
        ]);
        News::factory()->create([
            'category' => 'Kegiatan',
            'status' => 'published'
        ]);

        $response = $this->getJson("/api/news?category=Berita");

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
    }

    /**
     * Test showing a single published news.
     */
    public function test_can_show_published_news_details(): void
    {
        $news = News::factory()->create(['status' => 'published']);

        $response = $this->getJson("/api/news/{$news->slug}");

        $response->assertStatus(200);
        $response->assertJsonPath('data.title', $news->title);
    }

    /**
     * Test that draft news are not found.
     */
    public function test_cannot_see_draft_news_details(): void
    {
        $news = News::factory()->create(['status' => 'draft']);

        $response = $this->getJson("/api/news/{$news->slug}");

        $response->assertStatus(404);
    }
}
