<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\DTO\WeatherDTO;
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
        $now = new WeatherDTO(
            city: 'Kyiv',
            temperature: 10.0,
            description: 'clear',
            windSpeed: 3.0,
            rawData: []
        );
        $forecast = ['main' => ['temp' => 25]];

        $result = $this->service->hasSevereTemperature($now, $forecast);
        $this->assertTrue($result);
    }

    public function testHasSevereTemperatureFalse(): void
    {
        $now = new WeatherDTO(
            city: 'Kyiv',
            temperature: 10.0,
            description: 'cloudy',
            windSpeed: 4.0,
            rawData: []
        );
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
