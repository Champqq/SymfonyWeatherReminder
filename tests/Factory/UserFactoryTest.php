<?php

declare(strict_types=1);

namespace App\Tests\Factory;

use App\Factory\UserFactory;
use PHPUnit\Framework\TestCase;
use App\DTO\RegisterRequest;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFactoryTest extends TestCase
{
    public function testCreateUser(): void
    {
        $dto = new RegisterRequest();
        $dto->email = 'test@example.com';
        $dto->password = 'test';

        $passwordHasher = $this->createMock(UserPasswordHasherInterface::class);
        $passwordHasher->expects($this->once())
            ->method('hashPassword')
            ->willReturn('hashed_password');

        $factory = new UserFactory($passwordHasher);
        $user = $factory->create($dto);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('test@example.com', $user->getEmail());
        $this->assertEquals('hashed_password', $user->getPassword());
    }
}

