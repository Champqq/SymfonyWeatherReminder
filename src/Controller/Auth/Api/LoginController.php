<?php

declare(strict_types=1);

namespace App\Controller\Auth\Api;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class LoginController
{
    #[Route('/api/login', name: 'api_login', methods: ['POST'])]
    public function login(
        #[CurrentUser] ?UserInterface $user,
        JWTTokenManagerInterface $jwtManager,
    ): JsonResponse {
        if (!$user) {
            return new JsonResponse(['error' => 'Invalid credentials.'], Response::HTTP_UNAUTHORIZED);
        }

        $token = $jwtManager->create($user);

        return new JsonResponse(
            [
                'token' => $token,
                'email' => $user->getUserIdentifier(),
                'roles' => $user->getRoles(),
            ]
        );
    }
}
