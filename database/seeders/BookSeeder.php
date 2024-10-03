<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Author; 
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 100 sample authors
        $authors = Author::factory()->count(100)->create();

        // For each author, create 3 sample books
        foreach ($authors as $author) {
            Book::factory()->count(3)->create([
                'author_id' => $author->id,
            ]);
        }
    }
}
