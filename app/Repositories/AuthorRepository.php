<?php

namespace App\Repositories;

use App\Models\Author;
use Illuminate\Support\Collection;
use Exception;

class AuthorRepository
{
    // Retrieve a list of all authors
    public function all(): Collection
    {
        try {
            return Author::all();
        } catch (Exception $e) {
            throw new Exception('Failed to retrieve all authors: ' . $e->getMessage());
        }
    }

    // Retrieve details of a specific author
    public function find(int $id): ?Author
    {
        try {
            return Author::find($id);
        } catch (Exception $e) {
            throw new Exception('Failed to retrieve the author: ' . $e->getMessage());
        }
    }

    // Create a new author
    public function create(array $data): Author
    {
        try {
            return Author::create($data);
        } catch (Exception $e) {
            throw new Exception('Failed to create a new author: ' . $e->getMessage());
        }
    }

    // Update an existing author
    public function update(int $id, array $data): bool
    {
        try {
            $author = $this->find($id);

            if (!$author) {
                return false;
            }

            return $author->update($data);
        } catch (Exception $e) {
            throw new Exception('Failed to update the author: ' . $e->getMessage());
        }
    }

    // Delete an author
    public function delete(int $id): bool
    {
        try {
            $author = $this->find($id);

            if (!$author) {
                return false;
            }

            return $author->delete();
        } catch (Exception $e) {
            throw new Exception('Failed to delete the author: ' . $e->getMessage());
        }
    }

    // Retrieve all books for a specific author
    public function books(int $id): Collection
    {
        try {
            $author = $this->find($id);

            if (!$author) {
                return collect(); // Return empty collection if author not found
            }

            return $author->books;
        } catch (Exception $e) {
            throw new Exception('Failed to retrieve books for the author: ' . $e->getMessage());
        }
    }
}
