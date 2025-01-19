<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        Role::factory()->create([
            'name' => 'ADMIN',
            'description' => 'Administrator',
        ]);

        Role::factory()->create([
            'name' => 'CAPTURISTA',
            'description' => 'Capturista',
        ]);

        User::factory()->create([
            'name' => 'ADMIN',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin'),
            'role_id' => 1,
        ]);
    }
}
