<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\SubscriptionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SubscriptionRepository::class)]
class Subscription
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $city = null;

    #[ORM\Column(type: Types::TIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $time = null;

    #[ORM\Column(nullable: true)]
    private ?bool $enabled = null;

    #[ORM\ManyToOne(inversedBy: 'subscriptions')]
    private ?User $user = null;

    #[ORM\Column(type: 'boolean')]
    private bool $receiveEmergencies = true;

    #[ORM\Column(type: 'boolean')]
    private bool $receiveSms = false;

    #[ORM\Column(type: 'boolean')]
    private bool $receiveEmail = true;

    public function getReceiveEmail(): bool
    {
        return $this->receiveEmail;
    }

    public function setReceiveEmail(bool $receiveEmail): self
    {
        $this->receiveEmail = $receiveEmail;

        return $this;
    }

    public function getReceiveSms(): bool
    {
        return $this->receiveSms;
    }

    public function setReceiveSms(bool $receiveSms): self
    {
        $this->receiveSms = $receiveSms;

        return $this;
    }

    public function getReceiveEmergencies(): bool
    {
        return $this->receiveEmergencies;
    }

    public function setReceiveEmergencies(bool $receiveEmergencies): self
    {
        $this->receiveEmergencies = $receiveEmergencies;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getTime(): ?\DateTimeInterface
    {
        return $this->time;
    }

    public function setTime(?\DateTimeInterface $time): static
    {
        $this->time = $time;

        return $this;
    }

    public function isEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(?bool $enabled): static
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
