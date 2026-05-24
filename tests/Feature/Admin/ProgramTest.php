<?php

namespace Tests\Feature\Admin;

use App\Models\Program;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProgramTest extends TestCase
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

    public function test_admin_can_view_programs_index()
    {
        Program::factory()->count(3)->create();

        $response = $this->actingAs($this->admin)->get(route('admin.programs.index'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Admin/Programs/Index')
            ->has('programs', 3)
        );
    }

    public function test_admin_can_create_program()
    {
        Storage::fake('public');

        $data = [
            'title' => 'New Program',
            'description' => 'Program description',
            'is_active' => true,
            'order' => 1,
            'icon' => 'fas fa-check',
            'image' => UploadedFile::fake()->image('program.jpg'),
        ];

        $response = $this->actingAs($this->admin)->post(route('admin.programs.store'), $data);

        $response->assertRedirect(route('admin.programs.index'));
        $this->assertDatabaseHas('programs', [
            'title' => 'New Program',
            'is_active' => true,
        ]);

        $program = Program::first();
        Storage::disk('public')->assertExists($program->image);
    }

    public function test_admin_can_update_program()
    {
        Storage::fake('public');
        $program = Program::factory()->create();

        $data = [
            'title' => 'Updated Program Title',
            'description' => 'Updated description',
            'is_active' => false,
            'order' => 2,
        ];

        $response = $this->actingAs($this->admin)->put(route('admin.programs.update', $program), $data);

        $response->assertRedirect(route('admin.programs.index'));
        $this->assertDatabaseHas('programs', [
            'id' => $program->id,
            'title' => 'Updated Program Title',
            'is_active' => false,
        ]);
    }

    public function test_admin_can_delete_program()
    {
        Storage::fake('public');
        $program = Program::factory()->create(['image' => 'programs/old.jpg']);
        Storage::disk('public')->put('programs/old.jpg', 'fake content');

        $response = $this->actingAs($this->admin)->delete(route('admin.programs.destroy', $program));

        $response->assertRedirect(route('admin.programs.index'));
        $this->assertDatabaseMissing('programs', ['id' => $program->id]);
        Storage::disk('public')->assertMissing('programs/old.jpg');
    }

    public function test_non_admin_cannot_access_programs()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get(route('admin.programs.index'));
        $response->assertStatus(403);
    }
}
