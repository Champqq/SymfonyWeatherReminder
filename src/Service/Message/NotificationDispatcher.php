<?php

declare(strict_types=1);

namespace App\Service\Message;

use App\Entity\Subscription;
use App\Service\Message\Builder\NotificationBuilder;
use App\Service\Message\Builder\RecommendationService;
use App\Service\Message\Sender\EmailSender;
use App\Service\Message\Sender\SmsSender;
use App\Service\Weather\Provider\WeatherApiProvider;
use App\Service\Weather\WeatherSaver;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Vonage\Client\Exception\Exception;

class NotificationDispatcher
{
    public function __construct(
        private WeatherApiProvider $weatherProvider,
        private RecommendationService $recommendationService,
        private EmailSender $emailSender,
        private SmsSender $smsSender,
        private WeatherSaver $weatherSaver,
        private NotificationBuilder $builder,
    ) {
    }

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     */
    public function dispatch(Subscription $subscription): void
    {
        $city = $subscription->getCity();
        $user = $subscription->getUser();

        $current = $this->weatherProvider->getCurrentWeather($city);
        $forecast = $this->weatherProvider->getForecast($city)[0] ?? null;

        $this->weatherSaver->saveWeather($current);

        $recommendation = $this->recommendationService->getRecommendation($forecast->getDescription(), $forecast->getTemperature()) ?? 'No recommendation.';

        [$subject, $text] = $this->builder->buildNotification($subscription, $current, $forecast, $recommendation);

        $this->sendNotifications($subscription, $user->getEmail(), $user->getPhoneNumber(), $subject, $text);
    }

    /**
     * @throws \Psr\Http\Client\ClientExceptionInterface
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     * @throws Exception
     */
    private function sendNotifications(Subscription $subscription, string $email, ?string $phone, string $subject, string $text): void
    {
        if ($subscription->getReceiveEmail()) {
            $this->emailSender->send($email, $subject, $text);
        }

        if ($phone && $subscription->getReceiveSms()) {
            $this->smsSender->send($phone, $text);
        }
    }
}
