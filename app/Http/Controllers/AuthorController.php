<?php

namespace App\Http\Controllers;

use App\Repositories\AuthorRepository;
use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException; // Import the ValidationException class
use Exception;

class AuthorController extends Controller
{
    protected $authorRepository;

    public function __construct(AuthorRepository $authorRepository)
    {
        $this->authorRepository = $authorRepository;
    }

    // Retrieve a list of all authors
    public function index()
    {
        try {
            $authors = $this->authorRepository->all();

            if ($authors->isEmpty()) {
                return response()->json(['message' => 'No authors found'], Response::HTTP_NOT_FOUND);
            }

            return response()->json($authors, Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to retrieve authors', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Retrieve details of a specific author
    public function show($id)
    {
        try {
            $author = $this->authorRepository->find($id);

            if (!$author) {
                return response()->json(['message' => 'Author not found'], Response::HTTP_NOT_FOUND);
            }

            return response()->json($author, Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to retrieve author', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Create a new author
    public function store(StoreAuthorRequest $request)
    {
        try {
            // The validation failure will automatically be handled by the framework
            $author = $this->authorRepository->create($request->only(['name', 'birth_date', 'bio']));
            return response()->json($author, Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to create author', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Update an existing author
    public function update(UpdateAuthorRequest $request, $id)
    {
        try {
            // The validation failure will automatically be handled by the framework
            $authorUpdated = $this->authorRepository->update($id, $request->only(['name', 'birth_date', 'bio']));

            if (!$authorUpdated) {
                return response()->json(['message' => 'Author not found or could not be updated'], Response::HTTP_NOT_FOUND);
            }

            return response()->json($this->authorRepository->find($id), Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to update author', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Delete an author
    public function destroy($id)
    {
        try {
            $deleted = $this->authorRepository->delete($id);

            if (!$deleted) {
                return response()->json(['message' => 'Author not found'], Response::HTTP_NOT_FOUND);
            }

            return response()->json(['message' => 'Author deleted'], Response::HTTP_NO_CONTENT);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to delete author', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Retrieve all books for a specific author
    public function books($id)
    {
        try {
            $author = $this->authorRepository->find($id);

            if (!$author) {
                return response()->json(['message' => 'Author not found'], Response::HTTP_NOT_FOUND);
            }

            $books = $this->authorRepository->books($id);

            if ($books->isEmpty()) {
                return response()->json(['message' => 'No books found for this author'], Response::HTTP_NOT_FOUND);
            }

            return response()->json($books, Response::HTTP_OK);
        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to retrieve author books', 'error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
