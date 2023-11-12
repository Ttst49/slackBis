<?php

namespace App\Entity;

use App\Repository\PrivateMessageResponseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PrivateMessageResponseRepository::class)]
class PrivateMessageResponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Profile $author = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: 'privateMessageResponses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PrivateMessage $relatedToPrivateMessage = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getAuthor(): ?Profile
    {
        return $this->author;
    }

    public function setAuthor(?Profile $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getRelatedToPrivateMessage(): ?PrivateMessage
    {
        return $this->relatedToPrivateMessage;
    }

    public function setRelatedToPrivateMessage(?PrivateMessage $relatedToPrivateMessage): static
    {
        $this->relatedToPrivateMessage = $relatedToPrivateMessage;

        return $this;
    }
}
