<?php

declare(strict_types=1);

namespace App\Factory;

use App\DTO\RegisterRequest;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFactory
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function create(RegisterRequest $request): User
    {
        $user = new User();
        $user->setEmail($request->email);
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, $request->password)
        );

        return $user;
    }
}
