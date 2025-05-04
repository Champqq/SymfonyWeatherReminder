<?php

declare(strict_types=1);

namespace App\Service\Weather;

use App\Entity\Subscription;
use App\Message\SendWeatherReminderMessage;
use App\Service\Subscription\SubscriptionService;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class WeatherReminderService
{
    public function __construct(
        private MessageBusInterface $messageBus,
        private SubscriptionService $subscriptionService,
    ) {
    }

    /**
     * @throws ExceptionInterface
     */
    public function send(): void
    {
        $subscriptions = $this->subscriptionService->getActiveSubscriptions();
        if (empty($subscriptions)) {
            echo "No active subscriptions found, nothing to send\n";

            return;
        }

        foreach ($subscriptions as $subscription) {
            if ($this->shouldSendReminder($subscription)) {
                $message = new SendWeatherReminderMessage($subscription->getId());
                $this->messageBus->dispatch($message);
            }
        }
    }

    private function shouldSendReminder(Subscription $subscription): bool
    {
        $now = new \DateTime();
        $start = (clone $now)->modify('-5 minutes');
        $end = (clone $now)->modify('+5 minutes');
        $subTime = $subscription->getTime();
        $subToday = (clone $now)->setTime(
            (int) $subTime->format('H'),
            (int) $subTime->format('i')
        );

        return $subToday >= $start && $subToday <= $end;
    }
}
