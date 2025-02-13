<?php

namespace App\Controller;

use App\Services\AuthorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

final class AuthorController extends AbstractController
{
    public function __construct(
        private readonly AuthorService $authorService
    ) {
    }

    #[Route('/author', name: 'app_author', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['firstName']) || !isset($data['lastName'])) {
            return new JsonResponse(['error' => 'Поле firstName и lastName обязательны'], Response::HTTP_BAD_REQUEST
            );
        }

        $this->authorService->createAuthor($data['firstName'], $data['lastName']);

        return new JsonResponse(['success' => 'Автор создан'], Response::HTTP_CREATED);
    }

    #[Route('/author/{id}', name: 'app_author_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        try {
            $this->authorService->deleteAuthor($id);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse(['success' => 'Автор удален'], Response::HTTP_OK);
    }
}
