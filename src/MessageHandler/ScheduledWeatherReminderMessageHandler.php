<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\ScheduledWeatherReminderMessage;
use App\Message\SendWeatherReminderMessage;
use App\Service\Subscription\SubscriptionService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsMessageHandler]
class ScheduledWeatherReminderMessageHandler
{
    public function __construct(
        private SubscriptionService $subscriptionService,
        private MessageBusInterface $bus,
    ) {
    }

    /**
     * @throws ExceptionInterface
     */
    public function __invoke(ScheduledWeatherReminderMessage $message): void
    {
        $subscriptions = $this->subscriptionService->getActiveSubscriptions();

        foreach ($subscriptions as $subscription) {
            $this->bus->dispatch(new SendWeatherReminderMessage($subscription->getId()));
        }
    }
}
