<?php

namespace Tests\Feature;

use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BooksApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     *
     * @return void
     */
    /** @test */
    public function can_get_all_books()
    {
        $books = Book::factory(4)->create();
        // dd($books);

        // $this->get('/api/books')->dump();
        // --------------------------------------
        // dd(route('books.index'));
        // $this->get(route('books.index'))->dump();

        $this->getJson(route('books.index'))
            ->assertJsonFragment([
                'title' => $books[0]->title,
            ])->assertJsonFragment([
                'title' => $books[1]->title,
            ]);
    }

    /** @test */
    public function can_get_one_book()
    {
        $book = Book::factory()->create();

        // dd(route('books.show', $book));
        $this->getJson(route('books.show', $book))
            ->assertJsonFragment([
                'title' => $book->title,
            ]);
    }

    /** @test */
    public function can_create_books()
    {
        // test de regresión para verificar la validación de datos
        $this->postJson(route('books.store'), [])
            ->assertJsonValidationErrorFor('title');

        $newBookTitle = 'Un Nuevo Libro de Prueba';
        $this->postJson(route('books.store'), [
            'title' => $newBookTitle,
        ])->assertJsonFragment([
            'title' => $newBookTitle,
        ]);

        $this->assertDatabaseHas('books', [
            'title' => $newBookTitle,
        ]);
    }

    /** @test */
    public function can_update_books()
    {
        $book = Book::factory()->create();
        // test de regresión para verificar la validación de datos
        $this->patchJson(route('books.update', $book), [])
            ->assertJsonValidationErrorFor('title');

        $editBookTitle = 'Libro Editado';
        $this->patchJson(route('books.update', $book), [
            'title' => $editBookTitle,
        ])->assertJsonFragment([
            'title' => $editBookTitle,
        ]);

        $this->assertDatabaseHas('books', [
            'title' => $editBookTitle,
        ]);
    }

    /** @test */
    public function can_delete_books()
    {
        $book = Book::factory()->create();

        $this->deleteJson(route('books.destroy', $book))
            ->assertNoContent();

        $this->assertDatabaseCount('books', 0);
    }
}
