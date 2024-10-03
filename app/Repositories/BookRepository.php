<?php

namespace App\Repositories;

use App\Models\Book;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Exception;

class BookRepository
{
    // Retrieve all books
    public function all(int|null $perPage = 10, int|null $page = 1): Collection | \Illuminate\Contracts\Pagination\LengthAwarePaginator
    {
        try {
            if($page != null){
                return Book::paginate($perPage, ['*'], 'page', $page);
            }else{
                $books = Cache::remember('books', 60, function () {
                    return Book::all();
                });

                return $books;
            }
        } catch (Exception $e) {
            throw new Exception('Failed to retrieve all books: ' . $e->getMessage());
        }
    }

    // Find a specific book by its ID
    public function find(int $id): ?Book
    {
        try {
            return Book::find($id);
        } catch (Exception $e) {
            throw new Exception('Failed to retrieve the book: ' . $e->getMessage());
        }
    }

    // Create a new book
    public function create(array $data): Book
    {
        try {
            return Book::create($data);
        } catch (Exception $e) {
            throw new Exception('Failed to create a new book: ' . $e->getMessage());
        }
    }

    // Update an existing book
    public function update(int $id, array $data): bool
    {
        try {
            $book = Book::find($id);

            if (!$book) {
                return false;
            }

            return $book->update($data);
        } catch (Exception $e) {
            throw new Exception('Failed to update the book: ' . $e->getMessage());
        }
    }

    // Delete a book by its ID
    public function delete(int $id): bool
    {
        try {
            $book = Book::find($id);

            if (!$book) {
                return false;
            }

            return $book->delete();
        } catch (Exception $e) {
            throw new Exception('Failed to delete the book: ' . $e->getMessage());
        }
    }
}
