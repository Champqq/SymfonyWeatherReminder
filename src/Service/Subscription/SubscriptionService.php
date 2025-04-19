<?php

namespace App\Service\Subscription;

use App\DTO\SubscriptionRequest;
use App\Entity\Subscription;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class SubscriptionService
{
    public function __construct(
        private EntityManagerInterface $em,
    ) {
    }

    /**
     * @throws \Exception
     */
    public function createOrUpdate(Subscription $subscription, SubscriptionRequest $dto): Subscription
    {
        $subscription->setCity($dto->city);
        $subscription->setTime(new \DateTime($dto->time));
        $subscription->setEnabled($dto->enabled ?? true);
        $subscription->setReceiveEmergencies($dto->receiveEmergencies ?? false);
        $subscription->setReceiveSms($dto->receiveSms ?? false);
        $subscription->setReceiveEmail($dto->receiveEmail ?? false);

        return $subscription;
    }

    public function getSubscription(int $id): Subscription
    {
        return $this->em->getRepository(Subscription::class)->find($id);
    }

    public function getSubscriptions(#[CurrentUser] ?UserInterface $user): array
    {
        return $this->em->getRepository(Subscription::class)->findBy(['user' => $user]);
    }
}
