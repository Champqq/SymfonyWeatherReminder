<?php

declare(strict_types=1);

namespace App\Service\Message\Sender;

use Psr\Http\Client\ClientExceptionInterface;
use Vonage\Client;
use Vonage\Client\Exception\Exception;
use Vonage\SMS\Message\SMS;

class SmsSender
{
    public function __construct(private Client $client, private string $from)
    {
    }

    /**
     * @throws ClientExceptionInterface
     * @throws Exception
     */
    public function send(string $phoneNumber, string $text): void
    {
        $sms = new SMS($phoneNumber, $this->from, $text);
        $this->client->sms()->send($sms);
    }
}
