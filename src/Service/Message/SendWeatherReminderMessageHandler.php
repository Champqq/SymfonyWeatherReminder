<?php

declare(strict_types=1);

namespace App\Service\Message;

use App\Message\SendWeatherReminderMessage;
use App\Repository\SubscriptionRepository;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;

#[AsMessageHandler]
class SendWeatherReminderMessageHandler
{
    public function __construct(
        private SubscriptionRepository $subscriptionRepository,
        private NotificationDispatcher $dispatcher,
    ) {
    }

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function __invoke(SendWeatherReminderMessage $message): void
    {
        $subscription = $this->subscriptionRepository->find($message->getSubscriptionId());
        $this->dispatcher->dispatch($subscription);
    }
}
