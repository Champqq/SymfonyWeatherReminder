<?php

declare(strict_types=1);

namespace App\Service\Mapper;

use App\DTO\ForecastDTO;

class ForecastMapper
{
    /**
     * @throws \Exception
     */
    public function toDto(array $data): ForecastDTO
    {
        return new ForecastDTO(
            new \DateTimeImmutable($data['dt_txt']),
            $data['weather'][0]['description'] ?? 'No description',
            (float) ($data['main']['temp'] ?? 0)
        );
    }
}
