<?php

namespace App\Entity;

use App\Repository\GroupMessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: GroupMessageRepository::class)]
class GroupMessage
{
    #[Groups(["forGroupCreation","forGroupIndexing"])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(["forGroupCreation","forGroupIndexing"])]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Profile $author = null;

    #[Groups(["forGroupCreation","forGroupIndexing"])]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[Groups(["forGroupCreation","forGroupIndexing"])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\ManyToOne(inversedBy: 'groupMessages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?GroupConversation $groupConversation = null;
    

    public function getId(): ?int
    {
        return $this->id;
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

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

    public function getGroupConversation(): ?GroupConversation
    {
        return $this->groupConversation;
    }

    public function setGroupConversation(?GroupConversation $groupConversation): static
    {
        $this->groupConversation = $groupConversation;

        return $this;
    }


}
