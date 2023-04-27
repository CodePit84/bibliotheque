<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\SubscriptionRepository;

#[ORM\Entity(repositoryClass: SubscriptionRepository::class)]
class Subscription
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $subscriptionNumber = null;

    // #[ORM\Column]
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $subscriptionDate = null;

    #[ORM\Column]
    private ?float $subscriptionAmount = null;

    // #[ORM\Column]
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $subscriptionStartDate = null;

    // #[ORM\Column]
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $subscriptionEndDate = null;

    #[ORM\OneToOne(mappedBy: 'number', cascade: ['persist', 'remove'])]
    private ?RegisteredUser $registeredUser = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubscriptionNumber(): ?int
    {
        return $this->subscriptionNumber;
    }

    public function setSubscriptionNumber(int $subscriptionNumber): self
    {
        $this->subscriptionNumber = $subscriptionNumber;

        return $this;
    }

    public function getSubscriptionDate(): ?\DateTimeInterface
    {
        return $this->subscriptionDate;
    }

    public function setSubscriptionDate(\DateTimeInterface $subscriptionDate): self
    {
        $this->subscriptionDate = $subscriptionDate;

        return $this;
    }

    public function getSubscriptionAmount(): ?float
    {
        return $this->subscriptionAmount;
    }

    public function setSubscriptionAmount(float $subscriptionAmount): self
    {
        $this->subscriptionAmount = $subscriptionAmount;

        return $this;
    }

    public function getSubscriptionStartDate(): ?\DateTimeInterface
    {
        return $this->subscriptionStartDate;
    }

    public function setSubscriptionStartDate(\DateTimeInterface $subscriptionStartDate): self
    {
        $this->subscriptionStartDate = $subscriptionStartDate;

        return $this;
    }

    public function getSubscriptionEndDate(): ?\DateTimeInterface
    {
        return $this->subscriptionEndDate;
    }

    public function setSubscriptionEndDate(\DateTimeInterface $subscriptionEndDate): self
    {
        $this->subscriptionEndDate = $subscriptionEndDate;

        return $this;
    }

    public function getRegisteredUser(): ?RegisteredUser
    {
        return $this->registeredUser;
    }

    public function setRegisteredUser(RegisteredUser $registeredUser): self
    {
        // set the owning side of the relation if necessary
        if ($registeredUser->getId() !== $this) {
            $registeredUser->setId($this);
        }

        $this->registeredUser = $registeredUser;

        return $this;
    }

    public function __toString(){
        return $this->id;
    }
}
