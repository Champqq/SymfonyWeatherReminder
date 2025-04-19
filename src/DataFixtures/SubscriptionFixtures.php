<?php

namespace App\DataFixtures;

use App\Entity\Subscription;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

/**
 * @DependsOn({"App\DataFixtures\UserFixtures"})
 */
class SubscriptionFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @throws \Exception
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $subscription = new Subscription();
        $subscription->setCity($faker->city());
        $subscription->setTime(new \DateTime($faker->time()));
        $subscription->setEnabled($faker->boolean());
        $subscription->setReceiveSms($faker->boolean());
        $subscription->setReceiveEmail($faker->boolean());
        $subscription->setReceiveEmergencies($faker->boolean());
        $subscription->setUser($this->getReference(UserFixtures::FAKE_USER_REFERENCE, User::class));

        $manager->persist($subscription);
        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}
