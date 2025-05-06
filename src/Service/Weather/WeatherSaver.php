<?php

declare(strict_types=1);

namespace App\Service\Weather;

use App\DTO\WeatherDTO;
use App\Entity\Weather;
use App\Service\Entity\EntityService;
use App\Service\Mapper\WeatherMapper;

class WeatherSaver
{
    public function __construct(
        private EntityService $es,
        private WeatherMapper $weatherMapper,
    ) {
    }

    public function saveWeather(WeatherDTO $weatherDTO): Weather
    {
        $weather = $this->weatherMapper->toEntity($weatherDTO);

        $this->es->save($weather);

        return $weather;
    }
}
