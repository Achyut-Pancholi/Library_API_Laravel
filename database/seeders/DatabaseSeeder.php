<?php

namespace Database\Seeders;

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
        // 1 admin user
        User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ]);

        // 10 authors
        $authors = \App\Models\Author::factory(10)->create();

        // 30 books randomly assigned
        \App\Models\Book::factory(30)->make()->each(function ($book) use ($authors) {
            $book->author_id = $authors->random()->id;
            $book->save();
        });
    }
}
