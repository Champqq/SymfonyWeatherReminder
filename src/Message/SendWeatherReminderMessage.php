<?php

declare(strict_types=1);

namespace App\Message;

class SendWeatherReminderMessage
{
    public function __construct(private int $subscriptionId)
    {
    }

    public function getSubscriptionId(): int
    {
        return $this->subscriptionId;
    }
}
