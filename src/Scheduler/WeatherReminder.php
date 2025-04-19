<?php

namespace App\Scheduler;

use App\Message\SendWeatherReminderMessage;
use Symfony\Component\Scheduler\Attribute\AsScheduleProvider;
use Symfony\Component\Scheduler\RecurringMessage;
use Symfony\Component\Scheduler\Schedule;
use Symfony\Component\Scheduler\ScheduleProviderInterface;

#[AsScheduleProvider]
class WeatherReminder implements ScheduleProviderInterface
{
    public function getSchedule(): Schedule
    {
        return (new Schedule())
            ->add(RecurringMessage::every('1 hour', new SendWeatherReminderMessage()));
    }
}
