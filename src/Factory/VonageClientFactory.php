<?php

namespace App\Factory;

use Vonage\Client;
use Vonage\Client\Credentials\Basic;

class VonageClientFactory
{
    public static function create(string $key, string $secret): Client
    {
        return new Client(new Basic($key, $secret));
    }
}
