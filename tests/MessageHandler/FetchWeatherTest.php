<?php

declare(strict_types=1);

namespace App\Tests\MessageHandler;

use App\Message\ScheduledFetchWeatherMessage;
use App\MessageHandler\ScheduledFetchWeatherMessageHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

class FetchWeatherTest extends TestCase
{
    /**
     * @throws ExceptionInterface
     */
    public function testHandlerRunsCommand(): void
    {
        $commandMock = $this->createMock(Command::class);
        $commandMock->expects($this->once())
            ->method('run')
            ->with($this->isInstanceOf(ArrayInput::class), $this->isInstanceOf(NullOutput::class))
            ->willReturn(Command::SUCCESS);

        $handler = new ScheduledFetchWeatherMessageHandler($commandMock);
        $handler(new ScheduledFetchWeatherMessage());
    }
}
