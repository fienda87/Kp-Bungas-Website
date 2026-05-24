<?php

namespace Tests\Feature\Admin;

use App\Models\Gallery;
use App\Models\GalleryPhoto;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class GalleryTest extends TestCase
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

    public function test_admin_can_view_galleries_index()
    {
        Gallery::factory()->count(2)->create();

        $response = $this->actingAs($this->admin)->get(route('admin.galleries.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Admin/Galleries/Index')
            ->has('galleries', 2)
        );
    }

    public function test_admin_can_view_gallery_show()
    {
        $gallery = Gallery::factory()->create();
        GalleryPhoto::factory()->count(3)->create(['gallery_id' => $gallery->id]);

        $response = $this->actingAs($this->admin)->get(route('admin.galleries.show', $gallery));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Admin/Galleries/Show')
            ->has('gallery.photos', 3)
        );
    }

    public function test_admin_can_create_gallery_album()
    {
        Storage::fake('public');

        $data = [
            'album_name' => 'Event 2024',
            'description' => 'Description of event',
            'cover_image' => UploadedFile::fake()->image('cover.jpg'),
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.galleries.store'), $data);

        $response->assertRedirect(route('admin.galleries.index'));
        $this->assertDatabaseHas('galleries', [
            'album_name' => 'Event 2024',
        ]);

        $gallery = Gallery::first();
        Storage::disk('public')->assertExists($gallery->cover_image);
    }

    public function test_admin_can_upload_photos_to_gallery()
    {
        Storage::fake('public');
        $gallery = Gallery::factory()->create();

        $data = [
            'photos' => [
                UploadedFile::fake()->image('photo1.jpg'),
                UploadedFile::fake()->image('photo2.jpg'),
            ]
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.galleries.photos.upload', $gallery), $data);

        $response->assertRedirect();
        $this->assertDatabaseCount('gallery_photos', 2);
        
        $photo = GalleryPhoto::first();
        Storage::disk('public')->assertExists($photo->image_path);
    }

    public function test_admin_can_delete_photo()
    {
        Storage::fake('public');
        $photo = GalleryPhoto::factory()->create(['image_path' => 'galleries/photos/test.jpg']);
        Storage::disk('public')->put('galleries/photos/test.jpg', 'fake content');

        $response = $this->actingAs($this->admin)->delete(route('admin.galleries.photos.destroy', $photo));

        $response->assertRedirect();
        $this->assertDatabaseMissing('gallery_photos', ['id' => $photo->id]);
        Storage::disk('public')->assertMissing('galleries/photos/test.jpg');
    }

    public function test_admin_can_delete_gallery_and_its_photos()
    {
        Storage::fake('public');
        $gallery = Gallery::factory()->create(['cover_image' => 'galleries/cover.jpg']);
        $photo = GalleryPhoto::factory()->create([
            'gallery_id' => $gallery->id,
            'image_path' => 'galleries/photos/p1.jpg'
        ]);
        
        Storage::disk('public')->put('galleries/cover.jpg', 'fake content');
        Storage::disk('public')->put('galleries/photos/p1.jpg', 'fake content');

        $response = $this->actingAs($this->admin)->delete(route('admin.galleries.destroy', $gallery));

        $response->assertRedirect(route('admin.galleries.index'));
        $this->assertDatabaseMissing('galleries', ['id' => $gallery->id]);
        $this->assertDatabaseMissing('gallery_photos', ['id' => $photo->id]);
        
        Storage::disk('public')->assertMissing('galleries/cover.jpg');
        Storage::disk('public')->assertMissing('galleries/photos/p1.jpg');
    }

    public function test_non_admin_cannot_access_galleries()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('admin.galleries.index'));
        $response->assertStatus(403);
    }
}
