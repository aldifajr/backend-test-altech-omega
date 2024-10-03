<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use Illuminate\Http\Response;

class BookController extends Controller
{
    // Retrieve a list of all books
    public function index()
    {
        $books = Book::all();
        return response()->json($books, Response::HTTP_OK);
    }

    // Retrieve details of a specific book
    public function show($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(['message' => 'Book not found'], Response::HTTP_NOT_FOUND);
        }

        return response()->json($book, Response::HTTP_OK);
    }

    // Create a new book
    public function store(StoreBookRequest $request)
    {
        $book = Book::create($request->only(['title', 'author_id', 'description', 'published_at']));

        return response()->json($book, Response::HTTP_CREATED);
    }

    // Update an existing book
    public function update(UpdateBookRequest $request, $id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(['message' => 'Book not found'], Response::HTTP_NOT_FOUND);
        }

        $book->update($request->only(['title', 'author_id', 'description', 'published_at']));

        return response()->json($book, Response::HTTP_OK);
    }

    // Delete a book
    public function destroy($id)
    {
        $book = Book::find($id);

        if (!$book) {
            return response()->json(['message' => 'Book not found'], Response::HTTP_NOT_FOUND);
        }

        $book->delete();

        return response()->json(['message' => 'Book deleted'], Response::HTTP_NO_CONTENT);
    }
}
