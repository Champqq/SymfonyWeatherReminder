<?php

namespace App\Service\Weather;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class WeatherService
{
    private const BASE_URL = 'https://api.openweathermap.org/data/2.5/weather';
    private const FORECAST_URL = 'https://api.openweathermap.org/data/2.5/forecast';

    public function __construct(
        private HttpClientInterface $httpClient,
        private string $apiKey,
    ) {
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
        $response = $this->httpClient->request(
            'GET', self::FORECAST_URL, [
            'query' => array_merge($this->getDefaultParams(), ['q' => $city]),
            ]
        );
        $data = $response->toArray();

        $dailyForecasts = [];
        $addedDates = [];

        foreach ($data['list'] as $entry) {
            $date = explode(' ', $entry['dt_txt'])[0];

            if (!in_array($date, $addedDates) && str_contains($entry['dt_txt'], '12:00:00')) {
                $dailyForecasts[] = $entry;
                $addedDates[] = $date;
            }

            if (count($dailyForecasts) >= 5) {
                break;
            }
        }

        return $dailyForecasts;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getCurrentWeather(string $city): array
    {
        $response = $this->httpClient->request(
            'GET', self::BASE_URL, [
            'query' => array_merge($this->getDefaultParams(), ['q' => $city]),
            ]
        );

        return $response->toArray();
    }

    private function getDefaultParams(): array
    {
        return [
            'appid' => $this->apiKey,
            'units' => 'metric',
            'lang' => 'en',
        ];
    }
}
