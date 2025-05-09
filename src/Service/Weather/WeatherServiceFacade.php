<?php

declare(strict_types=1);

namespace App\Service\Weather;

use App\DTO\WeatherDTO;
use App\Service\Weather\Provider\WeatherProviderFactory;

class WeatherServiceFacade
{
    public function __construct(
        private WeatherProviderFactory $providerFactory,
    ) {
    }

    public function getCurrentWeather(string $city): WeatherDTO
    {
        $provider = $this->providerFactory->getProvider();
        return $provider->getCurrentWeather($city);
    }

    public function getForecast(string $city): array
    {
        $provider = $this->providerFactory->getProvider();
        return $provider->getForecast($city);
    }
}
