<?php

namespace App\Services;

use App\Repository\BookRepository;
use App\Repository\PublisherRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PublisherService
{
    public function __construct(
        private readonly PublisherRepository $publisherRepository,
        private readonly BookRepository $bookRepository
    ) {
    }

    /**
     * Редактирует издателя по ID
     */
    public function editPublisher(int $id, string $title, string $address): void
    {
        $publisher = $this->publisherRepository->find($id);

        if (!$publisher) {
            throw new NotFoundHttpException('Издательство с ID ' . $id . ' не найдено');
        }

        $publisher->setTitle($title);
        $publisher->setAddress($address);

        $this->publisherRepository->persist($publisher);
        $this->publisherRepository->save();
    }

    /**
     * Удаляет издательство по ID, если оно не имеет книг
     */
    public function deletePublisher(int $id): void
    {
        $publisher = $this->publisherRepository->find($id);

        if (!$publisher) {
            throw new HttpException(Response::HTTP_NOT_FOUND, 'Издательство с ID ' . $id . ' не найдено');
        }

        if ($this->bookRepository->findBy(['publisher' => $publisher])) {
            throw new HttpException(Response::HTTP_CONFLICT, 'Издательство с ID ' . $id . ' не может быть удалено, так как у него есть книги');
        }

        $this->publisherRepository->delete($publisher);
        $this->publisherRepository->save();
    }
}