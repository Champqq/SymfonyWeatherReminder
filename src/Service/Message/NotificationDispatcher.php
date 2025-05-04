<?php

declare(strict_types=1);

namespace App\Service\Message;

use App\DTO\WeatherDTO;
use App\Entity\Subscription;
use App\Service\Message\Builder\EmergencyNotification;
use App\Service\Message\Builder\ForecastNotification;
use App\Service\Message\Builder\RecommendationService;
use App\Service\Message\Sender\EmailSender;
use App\Service\Message\Sender\SmsSender;
use App\Service\Weather\WeatherAlertService;
use App\Service\Weather\WeatherSaver;
use App\Service\Weather\WeatherService;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class NotificationDispatcher
{
    public function __construct(
        private WeatherService $weatherService,
        private WeatherAlertService $alertService,
        private RecommendationService $recommendationService,
        private EmergencyNotification $emergencyBuilder,
        private ForecastNotification $forecastBuilder,
        private EmailSender $emailSender,
        private SmsSender $smsSender,
        private WeatherSaver $weatherSaver,
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
        $email = $user->getEmail();
        $phone = $user->getPhoneNumber();

        $current = $this->weatherService->getCurrentWeather($city);
        $forecast = $this->weatherService->getForecast($city)[0] ?? null;

        $this->weatherSaver->saveWeatherFromDTO($current);

        $description = $forecast['weather'][0]['description'];
        $temperature = $forecast['main']['temp'];
        $recommendation = $this->recommendationService->getRecommendation($description, $temperature) ?? 'No recommendation.';

        [$subject, $text] = $this->buildNotification($subscription, $current, $forecast, $recommendation);

        if ($subscription->getReceiveEmail()) {
            usleep(500000);
            $this->emailSender->send($email, $subject, $text);
        }

        if ($phone && $subscription->getReceiveSms()) {
            $this->smsSender->send($phone, $text);
        }
    }

    private function buildNotification(Subscription $subscription, WeatherDTO $current, array $forecast, string $recommendation): array
    {
        $description = $forecast['weather'][0]['description'];
        $temperature = $forecast['main']['temp'];

        $isTempAlert = $this->alertService->hasSevereTemperature($current, $forecast);
        $isDangerAlert = $this->alertService->hasDangerousConditions($forecast);

        if ($isTempAlert || $isDangerAlert) {
            return $this->emergencyBuilder->build($subscription, $description, $temperature, $recommendation);
        }

        return $this->forecastBuilder->build($subscription, $description, $temperature, $recommendation);
    }
}
