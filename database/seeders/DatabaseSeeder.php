<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'Pera Peric',
            'email' => 'pera@pera.com',
            'password' => bcrypt('perapera'),
        ]);

        $randomUsers = User::factory(9)->create();

        $users = $randomUsers->prepend($admin);

        Post::factory(100)->recycle($users)->create();
    }
}
