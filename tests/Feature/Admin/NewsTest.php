<?php

namespace Tests\Feature\Admin;

use App\Models\News;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class NewsTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();

        $adminRole = Role::factory()->create(['name' => 'Admin', 'slug' => 'admin']);
        $this->admin = User::factory()->create(['role_id' => $adminRole->id]);
    }

    public function test_admin_can_view_news_index()
    {
        News::factory()->count(5)->create();

        $response = $this->actingAs($this->admin)->get(route('admin.news.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Admin/News/Index')
            ->has('news.data', 5)
        );
    }

    public function test_admin_can_filter_news_by_status()
    {
        News::factory()->count(2)->create(['status' => 'draft']);
        News::factory()->count(3)->create(['status' => 'published']);

        $response = $this->actingAs($this->admin)->get(route('admin.news.index', [
            'status' => 'draft',
        ]));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Admin/News/Index')
            ->where('filters.status', 'draft')
            ->has('news.data', 2)
        );
    }

    public function test_admin_can_create_news_with_files()
    {
        Storage::fake('public');

        $data = [
            'title' => 'Test News Title',
            'category' => 'Berita',
            'content' => 'Test content for news.',
            'status' => 'published',
            'thumbnail' => UploadedFile::fake()->image('thumb.jpg'),
            'pdf_file' => UploadedFile::fake()->create('doc.pdf', 100, 'application/pdf'),
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.news.store'), $data);

        $response->assertRedirect(route('admin.news.index'));
        $this->assertDatabaseHas('news', [
            'title' => 'Test News Title',
            'category' => 'Berita',
            'status' => 'published',
        ]);

        $news = News::first();
        $this->assertNotNull($news->thumbnail);
        $this->assertNotNull($news->pdf_file);
        
        Storage::disk('public')->assertExists($news->thumbnail);
        Storage::disk('public')->assertExists($news->pdf_file);
    }

    public function test_admin_can_update_news()
    {
        Storage::fake('public');
        $news = News::factory()->create([
            'thumbnail' => 'news/thumbnails/old.jpg'
        ]);
        Storage::disk('public')->put('news/thumbnails/old.jpg', 'fake content');

        $data = [
            'title' => 'Updated News Title',
            'category' => 'Kegiatan',
            'content' => 'Updated content.',
            'status' => 'draft',
            'thumbnail' => UploadedFile::fake()->image('new_thumb.jpg'),
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.news.update', $news), $data);

        $response->assertRedirect(route('admin.news.index'));
        $this->assertDatabaseHas('news', [
            'id' => $news->id,
            'title' => 'Updated News Title',
            'status' => 'draft',
        ]);

        Storage::disk('public')->assertMissing('news/thumbnails/old.jpg');
        $news->refresh();
        Storage::disk('public')->assertExists($news->thumbnail);
    }

    public function test_admin_can_delete_news()
    {
        Storage::fake('public');
        $news = News::factory()->create([
            'thumbnail' => 'news/thumbnails/delete.jpg',
            'pdf_file' => 'news/pdfs/delete.pdf',
        ]);
        Storage::disk('public')->put('news/thumbnails/delete.jpg', 'fake content');
        Storage::disk('public')->put('news/pdfs/delete.pdf', 'fake content');

        $response = $this->actingAs($this->admin)->delete(route('admin.news.destroy', $news));

        $response->assertRedirect(route('admin.news.index'));
        $this->assertDatabaseMissing('news', ['id' => $news->id]);
        Storage::disk('public')->assertMissing('news/thumbnails/delete.jpg');
        Storage::disk('public')->assertMissing('news/pdfs/delete.pdf');
    }

    public function test_news_validation_rules()
    {
        $response = $this->actingAs($this->admin)->post(route('admin.news.store'), []);

        $response->assertSessionHasErrors(['title', 'category', 'content', 'status']);
    }

    public function test_non_admin_cannot_access_news()
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get(route('admin.news.index'));
        $response->assertStatus(403);
        
        $response = $this->actingAs($user)->post(route('admin.news.store'), []);
        $response->assertStatus(403);
    }
}
