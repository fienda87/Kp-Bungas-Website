<?php

namespace Tests\Feature\Api;

use App\Models\News;
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
        News::factory()->create(['category' => 'Berita', 'status' => 'published']);
        News::factory()->create(['category' => 'Kegiatan', 'status' => 'published']);
        News::factory()->create(['category' => 'Berita', 'status' => 'published']);

        $response = $this->getJson('/api/categories');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data'
        ]);
        $response->assertJsonCount(2, 'data');
        $response->assertJson([
            'data' => ['Berita', 'Kegiatan']
        ]);
    }
}
