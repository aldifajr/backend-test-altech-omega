<?php
namespace Tests\Feature;

use App\Models\Author;
use Tests\TestCase;
use Mockery;
use App\Repositories\AuthorRepository;
use Illuminate\Http\Response;
use PHPUnit\Framework\Attributes\Test;

class AuthorControllerTest extends TestCase
{
    protected $authorRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authorRepository = Mockery::mock(AuthorRepository::class);
        $this->app->instance(AuthorRepository::class, $this->authorRepository);
    }

    #[Test]
    public function it_can_retrieve_all_authors()
    {
        $authors = collect([['name' => 'Author 1'], ['name' => 'Author 2']]);

        $this->authorRepository->shouldReceive('all')->once()->andReturn($authors);

        $response = $this->getJson('/api/authors');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson($authors->toArray());
    }

    #[Test]
    public function it_returns_404_when_no_authors_found()
    {
        $this->authorRepository->shouldReceive('all')->once()->andReturn(collect());

        $response = $this->getJson('/api/authors');

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson(['message' => 'No authors found']);
    }
    #[Test]
    public function it_can_retrieve_an_author_by_id()
    {
        $author = new Author([
            'id' => 1, 
            'name' => 'Author 1',
            'bio' => 'Just a writer',
            'birth_date' => '1995-12-05'
        ]);

        $this->authorRepository->shouldReceive('find')->with(1)->once()->andReturn($author);

        $response = $this->getJson('/api/authors/1');
        
        $response->assertStatus(Response::HTTP_OK)
            ->assertJson($author->toArray());
    }

    #[Test]
    public function it_returns_404_when_author_not_found()
    {
        $this->authorRepository->shouldReceive('find')->with(1)->once()->andReturn(null);

        $response = $this->getJson('/api/authors/1');

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson(['message' => 'Author not found']);
    }
    #[Test]
    public function it_can_create_an_author()
    {
        $data = ['name' => 'New Author', 'birth_date' => '2023-10-01', 'bio' => 'Author bio'];
        $authorData = new Author($data);

        $this->authorRepository->shouldReceive('create')->with($data)->once()->andReturn($authorData);

        $response = $this->postJson('/api/authors', $data);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJson($authorData->toArray());
    }

    #[Test]
    public function it_returns_500_on_store_error()
    {
        $this->authorRepository->shouldReceive('create')->andThrow(new \Exception('Failed to create author'));

        $response = $this->postJson('/api/authors', ['name' => 'New Author', 'bio' => 'Just a writer', 'birth_date' => '1995-12-05']);

        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR)
            ->assertJson(['message' => 'Failed to create author']);
    }

    #[Test]
    public function it_can_update_an_author()
    {
        $data = ['name' => 'Updated Author', 'birth_date' => '2023-10-01', 'bio' => 'Updated bio'];
        $authorData = new Author($data);

        $this->authorRepository->shouldReceive('update')->with(1, $data)->once()->andReturn(true);
        $this->authorRepository->shouldReceive('find')->with(1)->once()->andReturn($authorData);

        $response = $this->putJson('/api/authors/1', $data);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson($authorData->toArray());
    }

    #[Test]
    public function it_returns_404_if_author_cannot_be_updated()
    {
        $this->authorRepository->shouldReceive('update')->with(1, Mockery::any())->once()->andReturn(false);

        $response = $this->putJson('/api/authors/1', ['name' => 'Updated Author']);

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson(['message' => 'Author not found or could not be updated']);
    }

    #[Test]
    public function it_can_delete_an_author()
    {
        $this->authorRepository->shouldReceive('delete')->with(1)->once()->andReturn(true);

        $response = $this->deleteJson('/api/authors/1');

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    #[Test]
    public function it_returns_404_if_author_cannot_be_deleted()
    {
        $this->authorRepository->shouldReceive('delete')->with(1)->once()->andReturn(false);

        $response = $this->deleteJson('/api/authors/1');

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson(['message' => 'Author not found']);
    }

    #[Test]
    public function it_can_retrieve_books_for_an_author()
    {
        $author = new Author(['id' => 1, 'name' => 'Author 1', 'bio' => 'Just a writer', '1995-12-05']);
        $books = collect([['title' => 'Book 1'], ['title' => 'Book 2']]);

        $this->authorRepository->shouldReceive('find')->with(1)->once()->andReturn($author);
        $this->authorRepository->shouldReceive('books')->with(1)->once()->andReturn($books);

        $response = $this->getJson('/api/authors/1/books');

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson($books->toArray());
    }

    #[Test]
    public function it_returns_404_if_no_books_found_for_author()
    {
        $author = new Author(['id' => 1, 'name' => 'Author 1']);

        $this->authorRepository->shouldReceive('find')->with(1)->once()->andReturn($author);
        $this->authorRepository->shouldReceive('books')->with(1)->once()->andReturn(collect());

        $response = $this->getJson('/api/authors/1/books');

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson(['message' => 'No books found for this author']);
    }

    #[Test]
    public function it_returns_404_if_author_not_found_when_retrieving_books()
    {
        $this->authorRepository->shouldReceive('find')->with(1)->once()->andReturn(null);

        $response = $this->getJson('/api/authors/1/books');

        $response->assertStatus(Response::HTTP_NOT_FOUND)
            ->assertJson(['message' => 'Author not found']);
    }

}

