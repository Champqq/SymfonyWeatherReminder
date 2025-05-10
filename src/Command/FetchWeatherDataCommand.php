<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\Subscription\SubscriptionService;
use App\Service\Weather\Provider\WeatherProviderInterface;
use App\Service\Weather\WeatherSaver;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

#[AsCommand(
    name: 'app:fetch-weather-data',
    description: 'Fetch the latest weather data',
)]
class FetchWeatherDataCommand extends Command
{
    public function __construct(
        private WeatherProviderInterface $weatherProvider,
        private SubscriptionService $subscriptionService,
        private WeatherSaver $weatherSaver,
    ) {
        parent::__construct();
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $subscriptions = $this->subscriptionService->getActiveSubscriptions();
        foreach ($subscriptions as $subscription) {
            $city = $subscription->getCity();
            $weather = $this->weatherProvider->getCurrentWeather($city);
            $this->weatherSaver->saveWeather($weather);
        }
        $output->writeln('<info>Weather data fetched successfully.</info>');

        return Command::SUCCESS;
    }
}
