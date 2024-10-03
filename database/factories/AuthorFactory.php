<?php

namespace Database\Factories;

use App\Models\Author;
use Illuminate\Database\Eloquent\Factories\Factory;

class AuthorFactory extends Factory
{
    protected $model = Author::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name(), // Random name for the author
            'bio' => $this->faker->paragraph(), // Random biography
            'birth_date' => $this->faker->date(), // Random birth date
        ];
    }
}
