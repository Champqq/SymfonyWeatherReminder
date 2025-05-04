<?php

declare(strict_types=1);

namespace App\Service\Weather;

use App\DTO\WeatherDTO;
use App\Entity\Weather;
use App\Service\Entity\EntityService;

class WeatherSaver
{
    public function __construct(
        private EntityService $es,
    ) {
    }

    public function saveWeatherFromDTO(WeatherDTO $weatherDTO): Weather
    {
        $weather = $weatherDTO->toEntity();

        $this->es->save($weather);

        return $weather;
    }
}
