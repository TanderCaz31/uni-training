<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->word() . ' ' . fake()->word(),
            'author_name' => fake()->name(),
            'isbn' => fake()->regexify('[0-9]{13}'),
            'description' => fake()->text(),
            'published_year' => fake()->year(),
            'total_copies' => fake()->numberBetween(8, 10),
            'available_copies' => fake()->numberBetween(4, 8),
        ];
    }
}
