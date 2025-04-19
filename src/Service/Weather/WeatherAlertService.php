<?php

namespace App\Service\Weather;

class WeatherAlertService
{
    public function hasSevereTemperature(array $now, array $forecast, int $threshold = 10): bool
    {
        return abs($forecast['main']['temp'] - $now['main']['temp']) >= $threshold;
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
