<?php

namespace App\Entity;

use App\Repository\BorrowRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BorrowRepository::class)]
class Borrow
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $loanDate = null;

    #[ORM\Column]
    private ?int $borrowingPeriod = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLoanDate(): ?\DateTimeImmutable
    {
        return $this->loanDate;
    }

    public function setLoanDate(\DateTimeImmutable $loanDate): self
    {
        $this->loanDate = $loanDate;

        return $this;
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
}
