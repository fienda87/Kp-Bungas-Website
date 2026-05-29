<?php

namespace Tests\Feature;

use App\Models\News;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutVite();
    }

    public function test_public_news_page_supports_search_and_category_filters(): void
    {
        News::factory()->create([
            'title' => 'Panen Raya Bungas',
            'category' => 'Kegiatan',
            'status' => 'published',
        ]);

        News::factory()->create([
            'title' => 'Berita Lain',
            'category' => 'Berita',
            'status' => 'published',
        ]);

        $response = $this->get(route('news.index', [
            'search' => 'Panen',
            'category' => 'Kegiatan',
        ]));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('News/Index')
            ->where('filters.search', 'Panen')
            ->where('filters.category', 'Kegiatan')
            ->has('news.data', 1)
        );
    }

    public function test_public_news_page_keeps_pagination_metadata(): void
    {
        News::factory()->count(12)->create([
            'status' => 'published',
        ]);

        $response = $this->get(route('news.index', ['page' => 2]));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('News/Index')
            ->has('news.data', 3)
            ->has('news.meta.links')
            ->where('news.meta.current_page', 2)
        );
    }

    public function test_draft_news_detail_is_not_publicly_accessible(): void
    {
        $news = News::factory()->create(['status' => 'draft']);

        $this->get(route('news.show', $news))->assertNotFound();
    }

    public function test_public_news_content_is_sanitized(): void
    {
        $news = News::factory()->create([
            'status' => 'published',
            'content' => '<p onclick="alert(1)">Aman</p><script>alert("x")</script>',
        ]);

        $response = $this->get(route('news.show', $news));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('News/Show')
            ->where('news.data.content', '<p>Aman</p>')
        );
    }
}
