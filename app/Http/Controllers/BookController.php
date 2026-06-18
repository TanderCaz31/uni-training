<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BookController extends Controller
{
    public function index(Request $request) {

        $availableFilter = $request->boolean('available');

        $q = Book::query()
            ->when($availableFilter, fn ($q) => $q->where('books.available_copies', '>', 0));

        $books = $q->get();

        return Inertia::render('Books/Index', ['books' => $books, 'available' => $availableFilter]);
    }

    public function show(Book $book) {
        return Inertia::render('Books/Show', ['book' => $book]);
    }
}
