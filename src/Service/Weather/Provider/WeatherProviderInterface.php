<?php

declare(strict_types=1);

namespace App\Service\Weather\Provider;

use App\DTO\WeatherDTO;

interface WeatherProviderInterface
{
    public function getCurrentWeather(string $city): WeatherDTO;

    public function getForecast(string $city): array;
}
