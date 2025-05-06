<?php

declare(strict_types=1);

namespace App\DTO;

class ForecastDTO
{
    public function __construct(
        private \DateTimeImmutable $date,
        private string $description,
        private float $temperature,
    ) {
    }

    public function getDate(): \DateTimeImmutable
    {
        return $this->date;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getTemperature(): float
    {
        return $this->temperature;
    }
}
