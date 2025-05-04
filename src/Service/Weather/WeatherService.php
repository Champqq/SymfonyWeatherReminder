<?php

declare(strict_types=1);

namespace App\Service\Weather;

use App\DTO\WeatherDTO;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class WeatherService
{
    public function __construct(
        private HttpWeatherClient $client,
        private WeatherParser $parser,
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
        $data = $this->client->fetchCurrent($city);
        return WeatherDTO::fromApiResponse($city, $data);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getForecast(string $city): array
    {
        $rawForecast = $this->client->fetchForecast($city);

        return $this->parser->parseDailyForecast($rawForecast);
    }
}
