<?php

namespace App\Entity;

use App\Repository\GroupMessageResponseRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: GroupMessageResponseRepository::class)]
class GroupMessageResponse
{
    #[Groups(["forGroupIndexing"])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(["forGroupIndexing"])]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[Groups(["forGroupIndexing"])]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Profile $author = null;

    #[Groups(["forGroupIndexing"])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?GroupMessage $relatedToGroupMessage = null;

    public function __construct()
    {
        $this->date = new \DateTime();
    }

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

    public function getRelatedToGroupMessage(): ?GroupMessage
    {
        return $this->relatedToGroupMessage;
    }

    public function setRelatedToGroupMessage(?GroupMessage $relatedToGroupMessage): static
    {
        $this->relatedToGroupMessage = $relatedToGroupMessage;

        return $this;
    }
}
