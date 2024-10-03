<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Author;
use Tests\TestCase;
use Mockery;
use App\Repositories\BookRepository;
use Illuminate\Http\Response;
use PHPUnit\Framework\Attributes\Test;

class BookControllerTest extends TestCase
{
    protected $bookRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bookRepository = Mockery::mock(BookRepository::class);
        $this->app->instance(BookRepository::class, $this->bookRepository);
    }

    #[Test]
    public function it_can_retrieve_all_books()
    {
        $books = collect([['title' => 'Book 1'], ['title' => 'Book 2']]);
        $this->bookRepository->shouldReceive('all')->with(10, 1)->once()->andReturn($books);

        $response = $this->getJson('/api/books?per_page=10&page=1');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson($books->toArray());
    }

    #[Test]
    public function it_returns_404_when_no_books_found()
    {
        $this->bookRepository->shouldReceive('all')->once()->andReturn(collect());

        $response = $this->getJson('/api/books');

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson(['message' => 'No books found']);
    }

    #[Test]
    public function it_can_retrieve_a_book_by_id()
    {
        $book = new Book(['id' => 1, 'title' => 'Book 1', 'description' => 'Description', 'publish_date' => '2024-01-01']);

        $this->bookRepository->shouldReceive('find')->with(1)->once()->andReturn($book);

        $response = $this->getJson('/api/books/1');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson($book->toArray());
    }

    #[Test]
    public function it_returns_404_when_book_not_found()
    {
        $this->bookRepository->shouldReceive('find')->with(1)->once()->andReturn(null);

        $response = $this->getJson('/api/books/1');

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson(['message' => 'Book not found']);
    }

    #[Test]
    public function it_can_create_a_book()
    {
        $author = Author::factory()->create(['name' => 'Author 1', 'bio' => 'Author bio', 'birth_date' => '1995-12-05']);
        
        $data = [
            'title' => 'New Book',
            'author_id' => 1,  
            'description' => 'Book description',
            'publish_date' => '2024-01-01'
        ];

        $book = new Book($data);

        $this->bookRepository->shouldReceive('create')->with($data)->once()->andReturn($book);

        $response = $this->postJson('/api/books', $data);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJson($book->toArray());
    }


    #[Test]
    public function it_returns_500_on_store_error()
    {
        $this->bookRepository->shouldReceive('create')->andThrow(new \Exception('Failed to create book'));

        $response = $this->postJson('/api/books', ['title' => 'New Book', 'author_id' => 1, 'description' => 'Book description', 'publish_date' => '2024-01-01']);

        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR)
            ->assertJson(['message' => 'Failed to create book']);
    }

    #[Test]
    public function it_can_update_a_book()
    {
        $author = new Author(['id' => 1, 'name' => 'Author 1', 'bio' => 'Author bio', 'birth_date' => '1995-12-05']);

        $data = ['title' => 'Updated Book', 'author_id' => 1, 'description' => 'Updated description', 'publish_date' => '2024-01-01'];
        $book = new Book($data);

        $this->bookRepository->shouldReceive('update')->with(1, $data)->once()->andReturn(true);
        $this->bookRepository->shouldReceive('find')->with(1)->once()->andReturn($book);

        $response = $this->putJson('/api/books/1', $data);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson($book->toArray());
    }

    #[Test]
    public function it_returns_404_if_book_cannot_be_updated()
    {
        $this->bookRepository->shouldReceive('update')->with(1, Mockery::any())->once()->andReturn(false);

        $response = $this->putJson('/api/books/1', ['title' => 'Updated Book']);

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson(['message' => 'Book not found or could not be updated']);
    }

    #[Test]
    public function it_can_delete_a_book()
    {
        $this->bookRepository->shouldReceive('delete')->with(1)->once()->andReturn(true);

        $response = $this->deleteJson('/api/books/1');

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    #[Test]
    public function it_returns_404_if_book_cannot_be_deleted()
    {
        $this->bookRepository->shouldReceive('delete')->with(1)->once()->andReturn(false);

        $response = $this->deleteJson('/api/books/1');

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson(['message' => 'Book not found']);
    }
}
