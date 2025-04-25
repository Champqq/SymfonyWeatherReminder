<?php

declare(strict_types=1);

namespace App\Service\Message\Sender;

use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class EmailSender
{
    public function __construct(private MailerInterface $mailer, private string $senderAddress)
    {
    }

    /**
     * @throws TransportExceptionInterface
     */
    public function send(string $to, string $subject, string $text): void
    {
        $email = (new Email())
            ->from($this->senderAddress)
            ->to($to)
            ->subject($subject)
            ->text($text);

        $this->mailer->send($email);
    }
}
