<?php

declare(strict_types=1);

namespace App\Service\Message\Builder;

use App\Entity\Subscription;

class EmergencyNotification
{
    public function build(Subscription $subscription, string $description, float $temp, string $recommendation): array
    {
        $city = $subscription->getCity();
        $subject = "Emergency warning for $city";
        $text = "Attention! $description, temperature is $temp C. $recommendation";

        return [$subject, $text];
    }
}
