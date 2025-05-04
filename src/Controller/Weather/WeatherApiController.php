<?php

declare(strict_types=1);

namespace App\Controller\Weather;

use App\Service\Weather\WeatherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class WeatherApiController extends AbstractController
{
    public function __construct(
        private WeatherService $weatherService,
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    #[Route('/api/weather/{city}', name: 'weather_by_city', methods: ['GET'])]
    public function getWeather(string $city): JsonResponse
    {
        $weather = $this->weatherService->getCurrentWeather($city);

        return new JsonResponse(
            [
                'city' => $weather->getCity(),
                'temperature' => $weather->getTemperature(),
                'description' => $weather->getDescription(),
                'wind_speed' => $weather->getWindSpeed(),
            ]
        );
    }
}
