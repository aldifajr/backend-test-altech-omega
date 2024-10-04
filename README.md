# Backend Test Altech Omega
The app is developed and tested with :
- PHP 8.x
- MySql 5.7.x

The app also comes with database migrations and seeds to make it easier to review.

## How to setup the project 
- Clone the project
- Type in `composer install`
- Copy .env.example to a new file .env and update the following mandatory env
```
DB_CONNECTION=
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

(Optional)
APP_TIMEZONE=Asia/Jakarta
```
- For best development practice type in `php artisan key:generate`, to generate app key for later use
- Run the database migration by typing in  `php artisan migrate`
- Run database seeds for dummy data by typing in `php artisan db:seed`, this will create 100 authors and 300 books

## How to run the project 

- Go to project root 
- Type in `php artisan serve`

## How to run unit test 
- Type in `php artisan test` to run the unit test

## Performance tuning information
I implemented simple and straighforward pagination and caching in "Retrieve All Books" endpoint to demonstrate the performance tuning.
```
namespace App\Repositories;

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
}
```
What can we do when database reach millions of data : 
- Review database indexes, while current database is quite simple, not many thatw e can index but there is good chance when the data grows, the database architecture will grow as well then we need to review the index to make sure frequent search column does not slow the database performance
- Caching to avoid reading the same data into database repeatedly
- Using pagination to retrive large sets of data
- Optimize queries in general, avoid executing multiple queries, make sure we only retrieve necessary data, review database relationships to optimize joined data

## App structure
- **Routers** : routes\api.php
- **Controllers** :  
  - app\Http\Controllers\AuthorController.php
  - app\Http\Controllers\BookController.php
- **Models** :
  - app\Models\Author.php
  - app\Repositories\BookRepository.php
- **Repositories** :
  - app\Repositories\AuthorRepository.php
  - app\Repositories\BookRepository.php
- **Requests** : app\Http\Requests\*
- **Migrattions & Factories & Seeders** : database\*
- **Tests** : tests\Feature\*

## Explaination of the design choices I made and the performance tuning techniques I implemented
- **Why I develop the solution with PHP over Python?** : I have years of experience working with PHP, it gives me the benefit of time, regardless of the performace which is debatable.
- **Why Laravel ?** : Laravel in term of size is bigger if we want to develop a simple API solution. But considering the time, it comes with the benefit of ready to use framework which cut a lot of development time.
- **Why do I put validation inside FormRequest class seperated from controller?** : The idea is to make the controller clean just to get result and return it as response.
- **Why do I put database queries into Repositories ?** : It will be easier to find and maintain by other developers, and why not put it in Models? To make it clear between database relationships and database queries.

## Additional infromation
- Response code :
  - GET :
    - Response::HTTP_NOT_FOUND 404
    - Response::HTTP_OK 200
    - Response::HTTP_INTERNAL_SERVER_ERROR 500
  - POST :
    - Response::HTTP_CREATED 201
    - Response::HTTP_UNPROCESSABLE_ENTITY 422
    - Response::HTTP_INTERNAL_SERVER_ERROR 500
  - PUT :
    - Response::HTTP_OK 200 
    - Response::HTTP_UNPROCESSABLE_ENTITY 422
    - Response::HTTP_INTERNAL_SERVER_ERROR 500
  - DELETE :
    - Response::HTTP_NO_CONTENT 204
    - Response::HTTP_NOT_FOUND 404
    - Response::HTTP_INTERNAL_SERVER_ERROR 500
- Routers  
![image](https://github.com/user-attachments/assets/78aab727-7e83-4756-a02e-9f800fe685a0)

