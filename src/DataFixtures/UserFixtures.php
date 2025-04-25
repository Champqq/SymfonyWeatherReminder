<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @DependsOn({})
 */
class UserFixtures extends Fixture
{
    public const FAKE_USER_REFERENCE = 'fakeUser';

    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $user = new User();
        $user->setEmail($faker->unique()->safeEmail());
        $user->setPhoneNumber($faker->phoneNumber());
        $user->setDefaultCity($faker->city());
        $user->setPassword($this->passwordHasher->hashPassword($user, 'password123'));

        $manager->persist($user);
        $manager->flush();

        $this->addReference(self::FAKE_USER_REFERENCE, $user);
    }
}
