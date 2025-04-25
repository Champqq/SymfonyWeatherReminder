<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Subscription;
use App\Service\Message\NotificationDispatcher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

#[AsCommand(
    name: 'app:send-weather-reminders',
    description: 'Sends weather reminders to ALL active subscriptions (no time check).',
)]
class SendWeatherRemindersCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $em,
        private NotificationDispatcher $dispatcher,
    ) {
        parent::__construct();
    }

    /**
     * @throws TransportExceptionInterface
     * @throws \Symfony\Component\Mailer\Exception\TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $subscriptions = $this->em->getRepository(Subscription::class)->findBy(['enabled' => true]);

        foreach ($subscriptions as $subscription) {
            $this->dispatcher->dispatch($subscription);
        }

        $output->writeln('All active subscriptions processed.');

        return Command::SUCCESS;
    }
}
