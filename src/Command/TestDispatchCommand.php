<?php

namespace App\Command;

use App\Message\SendWeatherReminderMessage;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'app:test-dispatch',
    description: 'Dispatch test weather reminder message',
)]
class TestDispatchCommand extends Command
{
    public function __construct(private MessageBusInterface $bus)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->bus->dispatch(new SendWeatherReminderMessage());
        $output->writeln('✅ Message dispatched!');

        return Command::SUCCESS;
    }
}
