<?php

namespace Tests\Feature\Admin;

use App\Models\Gallery;
use App\Models\GalleryPhoto;
use App\Models\Program;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProgramGalleryTest extends TestCase
{
    use RefreshDatabase;

    protected $adminUser;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->withoutVite();
        
        Storage::fake('public');
        
        $adminRole = Role::create([
            'name' => 'Administrator',
            'slug' => 'admin'
        ]);

        $this->adminUser = User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'role_id' => $adminRole->id
        ]);
    }

    /** @test */
    public function test_admin_can_see_programs_list()
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.programs.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function test_admin_can_create_program()
    {
        $image = UploadedFile::fake()->image('program.jpg');

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.programs.store'), [
                'title' => 'New Program',
                'description' => 'Description',
                'is_active' => true,
                'order' => 1,
                'icon' => 'home',
                'image' => $image
            ]);

        $response->assertRedirect(route('admin.programs.index'));
        $this->assertDatabaseHas('programs', ['title' => 'New Program']);
        
        $program = Program::where('title', 'New Program')->first();
        Storage::disk('public')->assertExists($program->image);
    }

    /** @test */
    public function test_admin_can_update_program()
    {
        $program = Program::create([
            'title' => 'Old Title',
            'description' => 'Old Description',
            'is_active' => true,
            'order' => 1
        ]);

        $response = $this->actingAs($this->adminUser)
            ->put(route('admin.programs.update', $program), [
                'title' => 'Updated Title',
                'description' => 'Updated Description',
                'is_active' => false,
                'order' => 2
            ]);

        $response->assertRedirect(route('admin.programs.index'));
        $this->assertDatabaseHas('programs', ['title' => 'Updated Title', 'is_active' => false]);
    }

    /** @test */
    public function test_admin_can_delete_program()
    {
        $image = UploadedFile::fake()->image('program.jpg');
        $path = $image->store('programs', 'public');
        
        $program = Program::create([
            'title' => 'To Delete',
            'description' => 'Description',
            'image' => $path,
            'is_active' => true,
            'order' => 1
        ]);

        $response = $this->actingAs($this->adminUser)
            ->delete(route('admin.programs.destroy', $program));

        $response->assertRedirect(route('admin.programs.index'));
        $this->assertDatabaseMissing('programs', ['id' => $program->id]);
        Storage::disk('public')->assertMissing($path);
    }

    /** @test */
    public function test_admin_can_see_galleries_list()
    {
        $response = $this->actingAs($this->adminUser)
            ->get(route('admin.galleries.index'));

        $response->assertStatus(200);
    }

    /** @test */
    public function test_admin_can_create_gallery()
    {
        $image = UploadedFile::fake()->image('cover.jpg');

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.galleries.store'), [
                'album_name' => 'New Album',
                'description' => 'Album Description',
                'cover_image' => $image
            ]);

        $response->assertRedirect(route('admin.galleries.index'));
        $this->assertDatabaseHas('galleries', ['album_name' => 'New Album']);
        
        $gallery = Gallery::where('album_name', 'New Album')->first();
        Storage::disk('public')->assertExists($gallery->cover_image);
    }

    /** @test */
    public function test_admin_can_upload_photos_to_gallery()
    {
        $gallery = Gallery::create(['album_name' => 'Album']);
        $photo = UploadedFile::fake()->image('photo.jpg');

        $response = $this->actingAs($this->adminUser)
            ->post(route('admin.galleries.photos.upload', $gallery), [
                'photos' => [$photo]
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('gallery_photos', ['gallery_id' => $gallery->id]);
        
        $galleryPhoto = GalleryPhoto::where('gallery_id', $gallery->id)->first();
        Storage::disk('public')->assertExists($galleryPhoto->image_path);
    }

    /** @test */
    public function test_admin_can_update_photo_details()
    {
        $gallery = Gallery::create(['album_name' => 'Album']);
        $photo = GalleryPhoto::create([
            'gallery_id' => $gallery->id,
            'image_path' => 'path.jpg',
            'caption' => 'Old Caption',
            'order' => 1
        ]);

        $response = $this->actingAs($this->adminUser)
            ->patch(route('admin.galleries.photos.update', $photo), [
                'caption' => 'New Caption',
                'order' => 2
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('gallery_photos', [
            'id' => $photo->id,
            'caption' => 'New Caption',
            'order' => 2
        ]);
    }

    /** @test */
    public function test_admin_can_delete_photo()
    {
        $gallery = Gallery::create(['album_name' => 'Album']);
        $image = UploadedFile::fake()->image('photo.jpg');
        $path = $image->store('galleries/photos', 'public');
        
        $photo = GalleryPhoto::create([
            'gallery_id' => $gallery->id,
            'image_path' => $path
        ]);

        $response = $this->actingAs($this->adminUser)
            ->delete(route('admin.galleries.photos.destroy', $photo));

        $response->assertRedirect();
        $this->assertDatabaseMissing('gallery_photos', ['id' => $photo->id]);
        Storage::disk('public')->assertMissing($path);
    }
}
