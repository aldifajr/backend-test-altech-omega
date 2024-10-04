# Backend Test Altech Omega
The app is developed and tested with :
- PHP 8.x
- MySql 5.7.x

The app also comes with database migrations and seeds to make it easier to review.

## How to setup the project 
- Clone the project
- Type in 'composer install'
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

## Performance Tuning Information
I implemented pagination and caching in "Retrieve All Books" endpoint.
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

## Additional infromation
- Routers  
![image](https://github.com/user-attachments/assets/78aab727-7e83-4756-a02e-9f800fe685a0)

