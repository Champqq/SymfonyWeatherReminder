<?php

declare(strict_types=1);

namespace App\Tests\MessageHandler;

use App\Entity\Subscription;
use App\Message\ScheduledWeatherReminderMessage;
use App\Message\SendWeatherReminderMessage;
use App\MessageHandler\ScheduledWeatherReminderMessageHandler;
use App\Service\Subscription\SubscriptionService;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\ExceptionInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class WeatherReminderTest extends TestCase
{
    /**
     * @throws Exception
     * @throws ExceptionInterface
     */
    public function testHandlerDispatchesReminders(): void
    {
        $subscription1 = $this->createConfiguredMock(Subscription::class, ['getId' => 1]);
        $subscription2 = $this->createConfiguredMock(Subscription::class, ['getId' => 2]);

        $serviceMock = $this->createMock(SubscriptionService::class);
        $serviceMock->expects($this->once())
            ->method('getActiveSubscriptions')
            ->willReturn([$subscription1, $subscription2]);

        $dispatched = [];

        $busMock = $this->createMock(MessageBusInterface::class);
        $busMock->method('dispatch')
            ->willReturnCallback(function ($message) use (&$dispatched) {
                $dispatched[] = $message;

                return new Envelope($message);
            });

        $handler = new ScheduledWeatherReminderMessageHandler($serviceMock, $busMock);
        $handler(new ScheduledWeatherReminderMessage());

        $this->assertCount(2, $dispatched);
        $this->assertInstanceOf(SendWeatherReminderMessage::class, $dispatched[0]);
        $this->assertEquals(1, $dispatched[0]->getSubscriptionId());

        $this->assertInstanceOf(SendWeatherReminderMessage::class, $dispatched[1]);
        $this->assertEquals(2, $dispatched[1]->getSubscriptionId());
    }
}
