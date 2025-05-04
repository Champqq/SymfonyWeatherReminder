<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\Weather\WeatherReminderService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\Exception\ExceptionInterface;

#[AsCommand(
    name: 'app:send-weather-reminders',
    description: 'Dispatch weather reminders for current time window',
)]
class SendWeatherRemindersCommand extends Command
{
    public function __construct(private WeatherReminderService $reminderService)
    {
        parent::__construct();
    }

    /**
     * @throws ExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->reminderService->send();
        $output->writeln('<info>Weather reminders dispatched successfully.</info>');
        return Command::SUCCESS;
    }
}
