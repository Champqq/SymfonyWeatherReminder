<?php

namespace App\Controller\Auth;

use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
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
            return new JsonResponse(['error' => 'Invalid credentials.'], 401);
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
