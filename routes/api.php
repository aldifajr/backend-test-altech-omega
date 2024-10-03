<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthorController;


Route::get('/authors', [AuthorController::class, 'index']); // GET /authors
Route::get('/authors/{id}', [AuthorController::class, 'show']); // GET /authors/{id}
Route::post('/authors', [AuthorController::class, 'store']); // POST /authors
Route::put('/authors/{id}', [AuthorController::class, 'update']); // PUT /authors/{id}
Route::delete('/authors/{id}', [AuthorController::class, 'destroy']); // DELETE /authors/{id}
Route::get('/authors/{id}/books', [AuthorController::class, 'books']); // GET /authors/{id}/books


Route::get('/books', [BookController::class, 'index']); // GET /books
Route::get('/books/{id}', [BookController::class, 'show']); // GET /books/{id}
Route::post('/books', [BookController::class, 'store']); // POST /books
Route::put('/books/{id}', [BookController::class, 'update']); // PUT /books/{id}
Route::delete('/books/{id}', [BookController::class, 'destroy']); // DELETE /books/{id}
