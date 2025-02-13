<?php

namespace App\Controller;

use App\Services\PublisherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

final class PublisherController extends AbstractController
{
    public function __construct(
        private readonly PublisherService $publisherService
    ) {
    }

    #[Route('/publisher/{id}', name: 'app_publisher_edit', methods: ['PUT'])]
    public function edit(int $id, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['title']) || !isset($data['address'])) {
            return new JsonResponse(['error' => 'Поле title и address обязательны'], Response::HTTP_BAD_REQUEST);
        }

        try {
            $this->publisherService->editPublisher($id, $data['title'], $data['address']);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse(['success' => 'Издатель обновлен'], Response::HTTP_OK);
    }

    #[Route('/publisher/{id}', name: 'app_publisher_delete', methods: ['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        try {
            $this->publisherService->deletePublisher($id);
        } catch (HttpException $e) {
            return new JsonResponse(['error' => $e->getMessage()], $e->getStatusCode());
        }

        return new JsonResponse(['success' => 'Издатель удален'], Response::HTTP_OK);
    }
}
