<?php

declare(strict_types=1);

namespace App\Service\Weather;

use App\Entity\Subscription;
use App\Service\Message\NotificationDispatcher;
use Doctrine\ORM\EntityManagerInterface;

class WeatherReminderService
{
    public function __construct(
        private EntityManagerInterface $em,
        private NotificationDispatcher $dispatcher,
    ) {
    }

    public function send(): void
    {
        $now = new \DateTime();
        $windowStart = (clone $now)->modify('-5 minutes');
        $windowEnd = (clone $now)->modify('+5 minutes');

        $subscriptions = $this->em->getRepository(Subscription::class)->findBy(['enabled' => true]);

        foreach ($subscriptions as $subscription) {
            $subTime = $subscription->getTime();

            $subToday = (clone $now)->setTime(
                (int) $subTime->format('H'),
                (int) $subTime->format('i')
            );

            if ($subToday >= $windowStart && $subToday <= $windowEnd) {
                $this->dispatcher->dispatch($subscription);
            }
        }
    }
}
