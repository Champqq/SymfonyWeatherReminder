<?php

declare(strict_types=1);

namespace App\Service\Weather\Provider;

use App\DTO\ForecastDTO;
use App\DTO\WeatherDTO;
use App\Service\Mapper\WeatherMapper;
use App\Service\Weather\HttpWeatherClient;
use App\Service\Weather\WeatherParser;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class WeatherApiProvider implements WeatherProviderInterface
{
    public function __construct(
        private HttpWeatherClient $weatherClient,
        private WeatherParser $parser,
        private WeatherMapper $weatherMapper,
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getCurrentWeather(string $city): WeatherDTO
    {
        $weather = $this->weatherClient->fetchCurrent($city);

        return $this->weatherMapper->toDTO($city, $weather);
    }

    /**
     * @return ForecastDTO[]
     *
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getForecast(string $city): array
    {
        $forecast = $this->weatherClient->fetchForecast($city);

        return $this->parser->parseDailyForecast($forecast);
    }
}
