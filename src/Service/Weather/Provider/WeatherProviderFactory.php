<?php

declare(strict_types=1);

namespace App\Service\Weather\Provider;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class WeatherProviderFactory
{
    public function __construct(
        private ParameterBagInterface $parameterBag,
        private WeatherApiProvider $weatherApiProvider,
    ) {
    }

    public function getProvider(): WeatherProviderInterface
    {
        $provider = $this->parameterBag->get('weather_provider');

        return match ($provider) {
            'weatherapi' => $this->weatherApiProvider,
        };
    }
}
