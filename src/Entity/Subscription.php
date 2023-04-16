<?php

namespace App\Entity;

use App\Repository\SubscriptionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SubscriptionRepository::class)]
class Subscription
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $subscriberNumber = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $subscriptionDate = null;

    #[ORM\Column]
    private ?float $subscriptionAmount = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $subscriptionStartDate = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $subscriptionEndDate = null;

    #[ORM\OneToOne(mappedBy: 'number', cascade: ['persist', 'remove'])]
    private ?RegisteredUser $registeredUser = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubscriberNumber(): ?int
    {
        return $this->subscriberNumber;
    }

    public function setSubscriberNumber(int $subscriberNumber): self
    {
        $this->subscriberNumber = $subscriberNumber;

        return $this;
    }

    public function getSubscriptionDate(): ?\DateTimeImmutable
    {
        return $this->subscriptionDate;
    }

    public function setSubscriptionDate(\DateTimeImmutable $subscriptionDate): self
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

    public function getSubscriptionStartDate(): ?\DateTimeImmutable
    {
        return $this->subscriptionStartDate;
    }

    public function setSubscriptionStartDate(\DateTimeImmutable $subscriptionStartDate): self
    {
        $this->subscriptionStartDate = $subscriptionStartDate;

        return $this;
    }

    public function getSubscriptionEndDate(): ?\DateTimeImmutable
    {
        return $this->subscriptionEndDate;
    }

    public function setSubscriptionEndDate(\DateTimeImmutable $subscriptionEndDate): self
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
        if ($registeredUser->getNumber() !== $this) {
            $registeredUser->setNumber($this);
        }

        $this->registeredUser = $registeredUser;

        return $this;
    }
}
