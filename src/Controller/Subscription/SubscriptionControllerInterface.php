<?php

declare(strict_types=1);

namespace App\Controller\Subscription;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

interface SubscriptionControllerInterface
{
    public function create(Request $request, #[CurrentUser] ?UserInterface $user): Response|JsonResponse;

    public function update(int $id, Request $request): Response|JsonResponse;

    public function delete(int $id): Response|JsonResponse;
}
