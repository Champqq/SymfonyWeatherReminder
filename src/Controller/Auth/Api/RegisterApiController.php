<?php

declare(strict_types=1);

namespace App\Controller\Auth\Api;

use App\DTO\RegisterRequest;
use App\Factory\UserFactory;
use App\Service\Entity\EntityService;
use App\Service\Request\RequestHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RegisterApiController extends AbstractController
{
    public function __construct(
        private UserFactory $userFactory,
        private EntityService $es,
    ) {
    }

    #[Route('/api/register', name: 'api_register', methods: ['POST'])]
    public function register(Request $request, RequestHandler $requestHandler): JsonResponse
    {
        $dto = $requestHandler->handle($request, RegisterRequest::class);

        $user = $this->userFactory->create($dto);

        $this->es->save($user);

        return $this->json(['message' => 'User registered successfully']);
    }
}
