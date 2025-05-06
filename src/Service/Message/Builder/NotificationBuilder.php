<?php

declare(strict_types=1);

namespace App\Service\Message\Builder;

use App\DTO\ForecastDTO;
use App\DTO\WeatherDTO;
use App\Entity\Subscription;
use App\Service\Weather\WeatherAlertService;

class NotificationBuilder
{
    public function __construct(
        private WeatherAlertService $alertService,
        private EmergencyNotification $emergencyBuilder,
        private ForecastNotification $forecastBuilder,
    ) {
    }

    public function buildNotification(Subscription $subscription, WeatherDTO $current, ForecastDTO $forecast, string $recommendation): array
    {
        $description = $forecast->getDescription();
        $temperature = $forecast->getTemperature();

        $isTempAlert = $this->alertService->hasSevereTemperature($current, $forecast);
        $isDangerAlert = $this->alertService->hasDangerousConditions($forecast);

        if ($isTempAlert || $isDangerAlert) {
            return $this->emergencyBuilder->build($subscription, $description, $temperature, $recommendation);
        }

        return $this->forecastBuilder->build($subscription, $description, $temperature, $recommendation);
    }
}
