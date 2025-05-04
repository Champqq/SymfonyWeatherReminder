<?php

declare(strict_types=1);

namespace App\Scheduler;

use App\Message\ScheduledFetchWeatherMessage;
use Symfony\Component\Scheduler\Attribute\AsScheduleProvider;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\Schedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;

#[AsScheduleProvider]
class FetchWeatherScheduler implements ScheduleProviderInterface
{
    public function getSchedule(): Schedule
    {
        return (new Schedule())
            ->add(RecurringMessage::every('30 minutes', new ScheduledFetchWeatherMessage()));
    }
}
