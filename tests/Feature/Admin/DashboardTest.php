<?php

namespace Tests\Feature\Admin;

use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\News;
use App\Models\Program;
use App\Models\Gallery;
use App\Models\GalleryPhoto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutVite();

        $adminRole = Role::create(['name' => 'admin']);
        $this->admin = User::factory()->create();
        $this->admin->assignRole($adminRole);
    }

    public function test_admin_can_view_dashboard()
    {
        News::factory()->count(5)->create();
        News::factory()->count(2)->create(['status' => 'draft', 'thumbnail' => null]);
        Program::factory()->count(3)->create();
        $gallery = Gallery::factory()->create();
        Gallery::factory()->create(['cover_image' => null]);
        GalleryPhoto::factory()->count(2)->create(['gallery_id' => $gallery->id]);

        $response = $this->actingAs($this->admin)->get(route('admin.dashboard'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Admin/Dashboard')
            ->has('stats', fn ($stats) => $stats
                ->where('news_count', 7)
                ->where('draft_news_count', 2)
                ->where('programs_count', 3)
                ->where('galleries_count', 2)
                ->where('photos_count', 2)
                ->etc()
            )
            ->has('attentionItems.draftNews', 2)
            ->has('attentionItems.emptyGalleries')
            ->has('recentNews')
            ->has('recentGalleries')
            ->has('quickLinks')
        );
    }

    public function test_non_admin_cannot_access_dashboard()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('admin.dashboard'));
        $response->assertStatus(403);
    }

    public function test_unauthenticated_user_cannot_access_dashboard()
    {
        $response = $this->get(route('admin.dashboard'));
        $response->assertRedirect(route('login'));
    }
}
