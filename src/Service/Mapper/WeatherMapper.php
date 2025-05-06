<?php

declare(strict_types=1);

namespace App\Service\Mapper;

use App\Entity\Weather;
use App\DTO\WeatherDTO;

class WeatherMapper
{
    public function toEntity(WeatherDTO $weatherDTO): Weather
    {
        $weather = new Weather();
        $weather->setCity($weatherDTO->city);
        $weather->setDate(new \DateTime());
        $weather->setTemperature($weatherDTO->temperature);
        $weather->setDescription($weatherDTO->description);
        $weather->setWindSpeed($weatherDTO->windSpeed);

        return $weather;
    }

    public function toDTO(string $city, array $data): WeatherDTO
    {
        return new WeatherDTO(
            $city,
            $data['main']['temp'],
            $data['weather'][0]['description'],
            $data['wind']['speed'],
        );
    }
}
