<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\Message\ScheduledFetchWeatherMessage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ScheduledFetchWeatherMessageHandler
{
    public function __construct(
        private Command $fetchWeatherCommand,
    ) {
    }

    /**
     * @throws ExceptionInterface
     */
    public function __invoke(ScheduledFetchWeatherMessage $message): void
    {
        $this->fetchWeatherCommand->run(new ArrayInput([]), new NullOutput());
    }
}
