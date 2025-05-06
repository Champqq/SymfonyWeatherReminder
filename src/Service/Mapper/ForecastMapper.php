<?php

declare(strict_types=1);

namespace App\Service\Mapper;

use App\DTO\ForecastDto;

class ForecastMapper
{
    /**
     * @throws \Exception
     */
    public function toDto(array $data): ForecastDto
    {
        return new ForecastDto(
            new \DateTimeImmutable($data['dt_txt']),
            $data['weather'][0]['description'] ?? 'No description',
            (float) ($data['main']['temp'] ?? 0)
        );
    }
}
