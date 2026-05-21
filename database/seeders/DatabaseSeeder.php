<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
        ]);

        User::factory()->create([
            'name' => 'Admin Kampung Bungas',
            'email' => 'admin@kampungbungas.id',
            'password' => bcrypt('admin123'),
            'role_id' => 1, // Admin
        ]);

        $this->call([
            NewsSeeder::class,
        ]);
    }
}
