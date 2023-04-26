<?php

namespace App\Entity;

use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Index;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\BookRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: BookRepository::class)]
#[ORM\Table(name: "book")]
#[ORM\Index(name: "book", columns: ["title", "summary"], flags: ["fulltext"])]
// #[ORM\Index(name: "author", columns: ["last_name", "first_name"], flags: ["fulltext"])]

/**
 * @ORM\Entity(repositoryClass=BookRepository::class)
 * @ORM\Table(name="book", indexes={@ORM\Index(columns={"title", "summary"}, flags={"fulltext"})})
 */

class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    // #[ORM\Column(nullable: true)]
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $releaseDate = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $summary = null;

    #[ORM\ManyToMany(targetEntity: Author::class, inversedBy: 'books')]
    private Collection $author;

    #[ORM\Column(length: 20)]
    private ?string $type = null;

    #[ORM\OneToMany(mappedBy: 'book', targetEntity: Copy::class)]
    private Collection $copies;

    public function __construct()
    {
        $this->author = new ArrayCollection();
        $this->copies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getReleaseDate(): ?\DateTimeInterface
    {
        return $this->releaseDate;
    }

    public function setReleaseDate(?DateTimeInterface $releaseDate): self
    {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): self
    {
        $this->summary = $summary;

        return $this;
    }

    /**
     * @return Collection<int, Author>
     */
    public function getAuthor(): Collection
    {
        return $this->author;
    }

    public function addAuthor(Author $author): self
    {
        if (!$this->author->contains($author)) {
            $this->author->add($author);
        }

        return $this;
    }

    public function removeAuthor(Author $author): self
    {
        $this->author->removeElement($author);

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, Copy>
     */
    public function getCopies(): Collection
    {
        return $this->copies;
    }

    public function addCopy(Copy $copy): self
    {
        if (!$this->copies->contains($copy)) {
            $this->copies->add($copy);
            $copy->setBook($this);
        }

        return $this;
    }

    public function removeCopy(Copy $copy): self
    {
        if ($this->copies->removeElement($copy)) {
            // set the owning side to null (unless already changed)
            if ($copy->getBook() === $this) {
                $copy->setBook(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->title;
    }
}
