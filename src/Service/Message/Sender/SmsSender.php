<?php

namespace App\Service\Message\Sender;

use Psr\Http\Client\ClientExceptionInterface;
use Vonage\Client;
use Vonage\Client\Credentials\Basic;
use Vonage\Client\Exception\Exception;
use Vonage\SMS\Message\SMS;

class SmsSender
{
    private Client $client;
    private string $from;

    public function __construct(string $vonageKey, string $vonageSecret, string $vonageFrom)
    {
        $this->client = new Client(new Basic($vonageKey, $vonageSecret));
        $this->from = $vonageFrom;
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
