<?php

namespace App\Controller\Weather;

use App\Service\Weather\WeatherService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class WeatherController extends AbstractController
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
    #[Route('/', name: 'home')]
    public function index(#[CurrentUser] ?UserInterface $user): Response
    {
        $city = $user?->getDefaultCity() ?? 'Kyiv';

        $weatherData = $this->weatherService->getForecast($city);

        return $this->render(
            'weather/index.html.twig', [
            'forecast' => $weatherData,
            'city' => $city,
            ]
        );
    }
}
