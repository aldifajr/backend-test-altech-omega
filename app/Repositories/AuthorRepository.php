<?php // app/Repositories/AuthorRepository.php

namespace App\Repositories;

use App\Models\Author;
use Illuminate\Database\Eloquent\Collection;

class AuthorRepository
{
    // Retrieve a list of all authors
    public function all(): Collection
    {
        return Author::all(); // SELECT * FROM authors;
    }

    // Retrieve details of a specific author
    public function find($id): ?Author
    {
        return Author::find($id); // SELECT * FROM authors WHERE id = $id;
    }

    // Create a new author
    public function create(array $data): Author
    {
        return Author::create($data); // INSERT INTO authors (name, birth_date, bio) VALUES (...);
    }

    // Update an existing author
    public function update($id, array $data): bool
    {
        $author = $this->find($id);
        return $author ? $author->update($data) : false; // UPDATE authors SET ... WHERE id = $id;
    }

    // Delete an author
    public function delete($id): bool
    {
        $author = $this->find($id);
        return $author ? $author->delete() : false; // DELETE FROM authors WHERE id = $id;
    }

    // Retrieve all books for a specific author
    public function books($id): Collection
    {
        $author = $this->find($id);
        return $author ? $author->books : collect(); // SELECT * FROM books WHERE author_id = $id;
    }
}
