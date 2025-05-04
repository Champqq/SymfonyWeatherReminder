<?php

declare(strict_types=1);

namespace App\Service\Weather;

use App\DTO\WeatherDTO;

class WeatherAlertService
{
    public function hasSevereTemperature(WeatherDTO $now, array $forecast, int $threshold = 10): bool
    {
        return abs($forecast['main']['temp'] - $now->getTemperature()) >= $threshold;
    }

    public function hasDangerousConditions(array $forecast): bool
    {
        $dangerWords = ['snow', 'storm', 'thunderstorm', 'rain', 'fog', 'ice', 'blizzard'];

        $description = strtolower($forecast['weather'][0]['description'] ?? '');

        foreach ($dangerWords as $word) {
            if (str_contains($description, $word)) {
                return true;
            }
        }

        return false;
    }
}
