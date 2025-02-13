<?php

namespace App\Services;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AuthorService
{
    public function __construct(
        private readonly AuthorRepository $authorRepository
    ) {
    }

    /**
     * Создает нового автора.
     */
    public function createAuthor(string $firstName, string $lastName): Author
    {
        $author = new Author();
        $author->setFirstName($firstName);
        $author->setLastName($lastName);

        $this->authorRepository->persist($author);
        $this->authorRepository->save();

        return $author;
    }

    /**
     * Удаляет автора по ID.
     */
    public function deleteAuthor(int $id): void
    {
        $author = $this->authorRepository->find($id);

        if (!$author) {
            throw new NotFoundHttpException('Автор с ID ' . $id . ' не найден.');
        }

        $this->authorRepository->delete($author);
        $this->authorRepository->save();
    }
}