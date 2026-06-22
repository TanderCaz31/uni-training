<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class BookCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_catalog_index_displays_books(): void
    {
        //Arrange: a signed-in user, and three books to list
        $user = User::factory()->create();
        Book::factory()->count(3)->create();

        //Act: visit the catalog index as that user
        $response = $this
            ->actingAs($user)
            ->get(route('books.index'));

        //Assert: the page loads and was handed exactly three books
        $response->assertOk();
        $response->assertInertia(
            fn (Assert $page) => $page
                ->component('Books/Index')
                ->has('books', Book::count())
        );
    }

    public function test_book_catalog_availability_filter(): void
    {
        //Arrange: Auth user, 3 available books and 2 unavailable
        $user = User::factory()->create();
        Book::factory()->count(3)->create(['available_copies' => 5]);
        Book::factory()->count(2)->create(['available_copies' => 0]);

        //Act: Navigate to the index while filtering for available books
        $response = $this
            ->actingAs($user)
            ->get(route('books.index', ['available' => 1]));

        //Assert: The page loads and only three books are returned
        $response->assertOk();
        $response->assertInertia(
            fn (Assert $page) => $page
                ->component('Books/Index')
                ->has('books', 3)
        );
    }

    public function test_book_show_page_displays_the_book(): void
    {
        //Arrange: Auth user, 1 Book
        $user = User::factory()->create();
        $book = Book::factory()->create();

        //Act: Navigate to the book's show page
        $response = $this
            ->actingAs($user)
            ->get(route('books.show', $book));

        //Assert: Confirm the book handed to the page is the same one that was created
        $response->assertOk();
        $response->assertInertia(
            fn (Assert $page) => $page
                ->component('Books/Show')
                ->where('book.id', $book->id)
                ->where('book.title', $book->title)
        );
    }
}
