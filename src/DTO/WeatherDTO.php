<?php

declare(strict_types=1);

namespace App\DTO;

use App\Entity\Weather;

class WeatherDTO
{
    private string $city;
    private float $temperature;
    private string $description;
    private float $windSpeed;
    private array $rawData;

    public function __construct(
        string $city,
        float $temperature,
        string $description,
        float $windSpeed,
        array $rawData = [],
    ) {
        $this->city = $city;
        $this->temperature = $temperature;
        $this->description = $description;
        $this->windSpeed = $windSpeed;
        $this->rawData = $rawData;
    }

    public static function fromApiResponse(string $city, array $data): self
    {
        return new self(
            $city,
            $data['main']['temp'],
            $data['weather'][0]['description'],
            $data['wind']['speed'],
            $data
        );
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

    public function getRawData(): array
    {
        return $this->rawData;
    }

    public function toEntity(): Weather
    {
        $weather = new Weather();
        $weather->setCity($this->city);
        $weather->setDate(new \DateTime());
        $weather->setTemperature($this->temperature);
        $weather->setDescription($this->description);
        $weather->setWindSpeed($this->windSpeed);

        return $weather;
    }
}
