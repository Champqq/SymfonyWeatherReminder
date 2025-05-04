<?php

declare(strict_types=1);

namespace App\Service\Weather;

class WeatherParser
{
    public function parseDailyForecast(array $data): array
    {
        $dailyForecasts = [];
        $addedDates = [];

        foreach ($data['list'] as $entry) {
            $date = explode(' ', $entry['dt_txt'])[0];

            if (!in_array($date, $addedDates) && str_contains($entry['dt_txt'], '12:00:00')) {
                $dailyForecasts[] = $entry;
                $addedDates[] = $date;
            }

            if (count($dailyForecasts) >= 5) {
                break;
            }
        }

        return $dailyForecasts;
    }
}
