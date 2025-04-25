<?php

declare(strict_types=1);

namespace App\Service\Message\Builder;

use App\Entity\Subscription;

class ForecastNotification
{
    public function build(Subscription $subscription, string $description, float $temp, string $recommendation): array
    {
        $city = $subscription->getCity();
        $subject = "Weather forecast for $city";
        $text = "Today in $city $description, temperature is $temp C. $recommendation";

        return [$subject, $text];
    }
}
