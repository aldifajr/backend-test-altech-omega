<?php

namespace App\Http\Controllers;

use App\Repositories\BookRepository;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use Illuminate\Http\Response;
use Exception;
use Illuminate\Support\Collection;

class BookController extends Controller
{
    protected $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    // Retrieve a list of all books
    public function index()
    {
        $perPage = request()->get('per_page');
        $page = request()->get('page');

        try {
            $books = $this->bookRepository->all($perPage, $page);

            if($books instanceof Collection){
                if ($books->isEmpty()) {
                    return response()->json(['message' => 'No books found'], Response::HTTP_NOT_FOUND);
                }
            }

            return response()->json($books, Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to retrieve books', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Retrieve details of a specific book
    public function show($id)
    {
        try {
            $book = $this->bookRepository->find($id);

            if (!$book) {
                return response()->json(['message' => 'Book not found'], Response::HTTP_NOT_FOUND);
            }

            return response()->json($book, Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to retrieve book', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Create a new book
    public function store(StoreBookRequest $request)
    {
        try {
            $book = $this->bookRepository->create($request->only(['title', 'author_id', 'description', 'publish_date']));
            return response()->json($book, Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to create book', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Update an existing book
    public function update(UpdateBookRequest $request, $id)
    {
        try {
            $bookUpdated = $this->bookRepository->update($id, $request->only(['title', 'author_id', 'description', 'publish_date']));

            if (!$bookUpdated) {
                return response()->json(['message' => 'Book not found or could not be updated'], Response::HTTP_NOT_FOUND);
            }

            return response()->json($this->bookRepository->find($id), Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to update book', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Delete a book
    public function destroy($id)
    {
        try {
            $deleted = $this->bookRepository->delete($id);

            if (!$deleted) {
                return response()->json(['message' => 'Book not found'], Response::HTTP_NOT_FOUND);
            }

            return response()->json(['message' => 'Book deleted'], Response::HTTP_NO_CONTENT);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to delete book', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}