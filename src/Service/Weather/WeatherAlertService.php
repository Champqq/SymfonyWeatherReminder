<?php

declare(strict_types=1);

namespace App\Service\Weather;

use App\DTO\ForecastDTO;
use App\DTO\WeatherDTO;

class WeatherAlertService
{
    public function hasSevereTemperature(WeatherDTO $now, ForecastDTO $forecast, int $threshold = 10): bool
    {
        return abs($forecast->getTemperature() - $now->getTemperature()) >= $threshold;
    }

    public function hasDangerousConditions(ForecastDTO $forecast): bool
    {
        $dangerWords = ['snow', 'storm', 'thunderstorm', 'rain', 'fog', 'ice', 'blizzard'];

        $description = strtolower($forecast->getDescription());

        foreach ($dangerWords as $word) {
            if (str_contains($description, $word)) {
                return true;
            }
        }

        return false;
    }
}
