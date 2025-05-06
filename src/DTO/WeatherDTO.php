<?php

declare(strict_types=1);

namespace App\DTO;

class WeatherDTO
{
    public string $city;
    public float $temperature;
    public string $description;
    public float $windSpeed;

    public function __construct(
        string $city,
        float $temperature,
        string $description,
        float $windSpeed,
    ) {
        $this->city = $city;
        $this->temperature = $temperature;
        $this->description = $description;
        $this->windSpeed = $windSpeed;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function getTemperature(): float
    {
        return $this->temperature;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getWindSpeed(): float
    {
        return $this->windSpeed;
    }
}
