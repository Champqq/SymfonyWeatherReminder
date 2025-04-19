<?php

namespace App\Tests\Service;

use App\DTO\SubscriptionRequest;
use App\Entity\Subscription;
use App\Service\Subscription\SubscriptionService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class SubscriptionServiceTest extends TestCase
{
    /**
     * @throws \Exception
     */
    public function testCreateOrUpdateSetsAllFieldsCorrectly(): void
    {
        $dto = new SubscriptionRequest();
        $dto->city = 'Kyiv';
        $dto->time = '12:30';
        $dto->enabled = true;
        $dto->receiveSms = true;
        $dto->receiveEmail = false;
        $dto->receiveEmergencies = true;

        $subscription = new Subscription();

        $emMock = $this->createMock(EntityManagerInterface::class);
        $service = new SubscriptionService($emMock);

        $service->createOrUpdate($subscription, $dto);

        $this->assertEquals('Kyiv', $subscription->getCity());
        $this->assertEquals('12:30', $subscription->getTime()->format('H:i'));
        $this->assertTrue($subscription->isEnabled());
        $this->assertTrue($subscription->getReceiveSms());
        $this->assertFalse($subscription->getReceiveEmail());
        $this->assertTrue($subscription->getReceiveEmergencies());
    }
}
