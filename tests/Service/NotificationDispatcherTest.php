<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\DTO\WeatherDTO;
use App\Entity\Subscription;
use App\Entity\User;
use App\Service\Message\Builder\EmergencyNotification;
use App\Service\Message\Builder\ForecastNotification;
use App\Service\Message\Builder\RecommendationService;
use App\Service\Message\NotificationDispatcher;
use App\Service\Message\Sender\EmailSender;
use App\Service\Message\Sender\SmsSender;
use App\Service\Weather\WeatherAlertService;
use App\Service\Weather\WeatherSaver;
use App\Service\Weather\WeatherService;
use PHPUnit\Framework\TestCase;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class NotificationDispatcherTest extends TestCase
{
    /**
     * @throws TransportExceptionInterface
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testDispatch(): void
    {
        $weatherService = $this->createMock(WeatherService::class);
        $alertService = $this->createMock(WeatherAlertService::class);
        $recommendationService = $this->createMock(RecommendationService::class);
        $emergencyBuilder = $this->createMock(EmergencyNotification::class);
        $forecastBuilder = $this->createMock(ForecastNotification::class);
        $emailSender = $this->createMock(EmailSender::class);
        $smsSender = $this->createMock(SmsSender::class);
        $weatherSaver = $this->createMock(WeatherSaver::class);


        $dispatcher = new NotificationDispatcher(
            $weatherService,
            $alertService,
            $recommendationService,
            $emergencyBuilder,
            $forecastBuilder,
            $emailSender,
            $smsSender,
            $weatherSaver
        );

        $user = new User();
        $user->setEmail('test@example.com');

        $subscription = new Subscription();
        $subscription->setUser($user);
        $subscription->setCity('Kyiv');
        $subscription->setEnabled(true);
        $subscription->setReceiveEmail(true);
        $subscription->setReceiveSms(false);

        $forecast = [
            'weather' => [['description' => 'clear sky']],
            'main' => ['temp' => 20],
            'wind' => ['speed' => 10],
        ];

        $weather = new WeatherDTO(
            city: 'Kyiv',
            temperature: 10.0,
            description: 'clear',
            windSpeed: 3.0,
            rawData: []
        );

        $weatherService->method('getCurrentWeather')->willReturn($weather);
        $weatherService->method('getForecast')->willReturn([$forecast]);
        $recommendationService->method('getRecommendation')->willReturn('Take a jacket.');
        $alertService->method('hasSevereTemperature')->willReturn(false);
        $alertService->method('hasDangerousConditions')->willReturn(false);
        $forecastBuilder->method('build')->willReturn(['Forecast subject', 'Forecast message']);

        $emailSender->expects($this->once())->method('send')
            ->with('test@example.com', 'Forecast subject', 'Forecast message');

        $dispatcher->dispatch($subscription);
    }
}
