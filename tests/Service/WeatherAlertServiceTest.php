<?php

namespace App\Tests\Service;

use App\Service\Weather\WeatherAlertService;
use PHPUnit\Framework\TestCase;

class WeatherAlertServiceTest extends TestCase
{
    private WeatherAlertService $service;

    protected function setUp(): void
    {
        $this->service = new WeatherAlertService();
    }

    public function testHasSevereTemperatureTrue(): void
    {
        $now = ['main' => ['temp' => 10]];
        $forecast = ['main' => ['temp' => 25]];

        $result = $this->service->hasSevereTemperature($now, $forecast);
        $this->assertTrue($result);
    }

    public function testHasSevereTemperatureFalse(): void
    {
        $now = ['main' => ['temp' => 10]];
        $forecast = ['main' => ['temp' => 12]];

        $result = $this->service->hasSevereTemperature($now, $forecast);
        $this->assertFalse($result);
    }

    public function testHasDangerousConditionsTrue(): void
    {
        $forecast = ['weather' => [['description' => 'torrential rain']]];
        $result = $this->service->hasDangerousConditions($forecast);
        $this->assertTrue($result);
    }

    public function testHasDangerousConditionsFalse(): void
    {
        $forecast = ['weather' => [['description' => 'clear sky']]];
        $result = $this->service->hasDangerousConditions($forecast);
        $this->assertFalse($result);
    }
}
