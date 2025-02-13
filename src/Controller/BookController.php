<?php

namespace App\Controller;

use App\Services\BookService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

final class BookController extends AbstractController
{
    public function __construct(
        private readonly BookService $bookService
    ) {
    }

    #[Route('/book', name: 'app_book_create', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['title']) || !isset($data['authorIds'])) {
            return new JsonResponse(['error' => 'Поле title и authorIds обязательны'], Response::HTTP_BAD_REQUEST);
        }

        $book = $this->bookService->createBookWithAuthor($data['title'], $data['year'], $data['authorIds']);

        return new JsonResponse(['success' => 'Книга создана'], Response::HTTP_CREATED);
    }

    #[Route('/book', name: 'app_book')]
    public function getAllWithAuthorPublisher(): JsonResponse
    {
        return $this->json($this->bookService->getAllBooksWithAuthorPublisher());
    }

    #[Route('/book/{id}', name: 'app_book_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        try {
            $this->bookService->deleteBook($id);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse(['success' => 'Книга удалена'], Response::HTTP_OK);
    }
}
