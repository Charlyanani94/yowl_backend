<?php

namespace Database\Seeders;

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

        // Créer un utilisateur admin s'il n'existe pas déjà
        if (!User::where('email', 'admin@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
                'role' => 'admin',
            ]);
        }

        // Créer un utilisateur normal s'il n'existe pas déjà
        if (!User::where('email', 'test@example.com')->exists()) {
            User::factory()->create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'role' => 'user',
            ]);
        }

        $this->call([
            CategorySeeder::class,
        ]);
        $this->call(PostSeeder::class);
        $this->call(ReportSeeder::class);
    }
}
