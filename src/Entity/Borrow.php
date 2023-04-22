<?php

namespace App\Entity;

use App\Repository\BorrowRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BorrowRepository::class)]
class Borrow
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $borrowingPeriod = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $borrowingDate = null;

    #[ORM\ManyToOne(inversedBy: 'borrows')]
    #[ORM\JoinColumn(nullable: false)]
    private ?RegisteredUser $registeredUser = null;

    #[ORM\ManyToOne(inversedBy: 'borrows')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Copy $copy = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $borrowingEndDate = null;

    #[ORM\Column]
    private ?bool $returned = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBorrowingPeriod(): ?int
    {
        return $this->borrowingPeriod;
    }

    public function setBorrowingPeriod(int $borrowingPeriod): self
    {
        $this->borrowingPeriod = $borrowingPeriod;

        return $this;
    }

    public function getBorrowingDate(): ?\DateTimeInterface
    {
        return $this->borrowingDate;
    }

    public function setBorrowingDate(\DateTimeInterface $borrowingDate): self
    {
        $this->borrowingDate = $borrowingDate;

        return $this;
    }

    public function getRegisteredUser(): ?RegisteredUser
    {
        return $this->registeredUser;
    }

    public function setRegisteredUser(?RegisteredUser $registeredUser): self
    {
        $this->registeredUser = $registeredUser;

        return $this;
    }

    public function getCopy(): ?Copy
    {
        return $this->copy;
    }

    public function setCopy(?Copy $copy): self
    {
        $this->copy = $copy;

        return $this;
    }

    public function getBorrowingEndDate(): ?\DateTimeInterface
    {
        return $this->borrowingEndDate;
    }

    public function setBorrowingEndDate(?\DateTimeInterface $borrowingEndDate): self
    {
        $this->borrowingEndDate = $borrowingEndDate;

        return $this;
    }

    public function isReturned(): ?bool
    {
        return $this->returned;
    }

    public function setReturned(bool $returned): self
    {
        $this->returned = $returned;

        return $this;
    }
}
