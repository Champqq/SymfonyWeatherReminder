<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\DTO\WeatherDTO;
use App\Entity\Subscription;
use App\Entity\User;
use App\Service\Message\Builder\NotificationBuilder;
use App\Service\Message\Builder\RecommendationService;
use App\Service\Message\NotificationDispatcher;
use App\Service\Message\Sender\EmailSender;
use App\Service\Message\Sender\SmsSender;
use App\Service\Weather\WeatherSaver;
use App\Service\Weather\WeatherServiceFacade;
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
        $weatherService = $this->createMock(WeatherServiceFacade::class);
        $recommendationService = $this->createMock(RecommendationService::class);
        $emailSender = $this->createMock(EmailSender::class);
        $smsSender = $this->createMock(SmsSender::class);
        $weatherSaver = $this->createMock(WeatherSaver::class);
        $notificationBuilder = $this->createMock(NotificationBuilder::class);

        $dispatcher = new NotificationDispatcher(
            $weatherService,
            $recommendationService,
            $emailSender,
            $smsSender,
            $weatherSaver,
            $notificationBuilder,
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
        );

        $weatherService->method('getCurrentWeather')->willReturn($weather);
        $weatherService->method('getForecast')->willReturn([$forecast]);
        $recommendationService->method('getRecommendation')->willReturn('Take a jacket.');
        $notificationBuilder->method('buildNotification')->willReturn(['Forecast subject', 'Forecast message']);

        $emailSender->expects($this->once())->method('send')
            ->with('test@example.com', 'Forecast subject', 'Forecast message');

        $dispatcher->dispatch($subscription);
    }
}
