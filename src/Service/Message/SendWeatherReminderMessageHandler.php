<?php

namespace App\Service\Message;

use App\Message\SendWeatherReminderMessage;
use App\Service\Weather\WeatherReminderService;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SendWeatherReminderMessageHandler
{
    public function __construct(private WeatherReminderService $reminderService)
    {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function __invoke(SendWeatherReminderMessage $message): void
    {
        $this->reminderService->send();
    }
}
