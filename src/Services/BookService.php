<?php

namespace App\Services;

use App\Entity\Book;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BookService
{
    public function __construct(
        private readonly BookRepository $bookRepository,
        private readonly AuthorRepository $authorRepository
    ) {
    }

    /**
     * Возвращает список книг с авторами и издательством
     */
    public function getAllBooksWithAuthorPublisher(): array
    {
        $results = $this->bookRepository->findAllBooksWithAuthorPublisher();

        $books = [];

        foreach ($results as $row) {
            $bookId = $row['id'];

            if (!isset($books[$bookId])) {
                $books[$bookId] = [
                    'title' => $row['title'],
                    'year' => $row['year'],
                    'publisherTitle' => $row['publisherTitle'],
                    'authors' => [],
                ];
            }

            if (!empty($row['lastName']) && !in_array($row['lastName'], $books[$bookId]['authors'])) {
                $books[$bookId]['authors'][] = $row['lastName'];
            }
        }

        return array_values($books);
    }

    /**
     * Создает книгу с существующими авторами по ID
     */
    public function createBookWithAuthor(string $title, int $year, array $authorIds): Book
    {
        $book = new Book();
        $book->setTitle($title);
        $book->setYear($year);

        $authors = [];
        foreach ($authorIds as $authorId) {
            $author = $this->authorRepository->find($authorId);
            if (!$author) {
                throw new NotFoundHttpException('Автор с ID ' . $authorId . ' не найден.');
            }
            $authors[] = $author;
        }

        foreach ($authors as $author) {
            $book->addAuthor($author);
        }

        $this->bookRepository->persist($book);
        $this->bookRepository->save();

        return $book;
    }

    /**
     * Удаляет книгу по ID
     */
    public function deleteBook(int $id): void
    {
        $book = $this->bookRepository->find($id);

        if (!$book) {
            throw new NotFoundHttpException('Книга c ID ' . $id . ' не найдена');
        }

        $this->bookRepository->delete($book);
        $this->bookRepository->save();
    }
}