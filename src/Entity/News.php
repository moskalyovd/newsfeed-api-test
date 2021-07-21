<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Repository\NewsRepository;

#[ORM\Entity(repositoryClass: NewsRepository::class)]
class News
{
    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column]
    private string $name;

    #[ORM\Column(type: Types::TEXT)]
    private string $body;

    #[ORM\Column]
    private string $author;

    #[ORM\Column]
    private \DateTime $createdAt;

    public function __construct(string $name, string $body, string $author)
    {
        $this->name = $name;
        $this->body = $body;
        $this->author = $author;
        $this->createdAt = new \DateTime();
    }

    public function edit(string $name, string $body, string $author): void
    {
        $this->name = $name;
        $this->body = $body;
        $this->author = $author;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getBody(): string
    {
        return $this->body;
    }
    
    public function getAuthor(): string
    {
        return $this->author;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }
}