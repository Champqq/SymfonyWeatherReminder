<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class SubscriptionRequest
{
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public string $city;

    #[Assert\NotBlank]
    public ?string $time = null;

    #[Assert\Type('bool')]
    public bool $enabled = true;

    #[Assert\Type('bool')]
    public bool $receiveSms = false;

    #[Assert\Type('bool')]
    public bool $receiveEmail = false;

    #[Assert\Type('bool')]
    public bool $receiveEmergencies = false;
}
