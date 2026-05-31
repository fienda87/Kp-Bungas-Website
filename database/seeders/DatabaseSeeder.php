<?php

namespace Database\Seeders;

use App\Models\User;
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

        $admin = User::factory()->create([
            'name' => 'Admin Kampung Bungas',
            'email' => 'admin@kampungbungas.id',
            'password' => bcrypt('admin123'),
        ]);

        $admin->assignRole('admin');

        $this->call([
            NewsSeeder::class,
        ]);
    }
}
