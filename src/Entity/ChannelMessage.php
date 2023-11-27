<?php

namespace App\Entity;

use App\Repository\ChannelMessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ChannelMessageRepository::class)]
class ChannelMessage
{
    #[Groups(["forChannel","forChannelMessages"])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(["forChannel","forChannelMessages"])]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Profile $author = null;

    #[Groups(["forChannel","forChannelMessages"])]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'channelMessages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Channel $associatedToChannel = null;

    #[Groups(["forChannel"])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    public function __construct(){
        $this->date = new \DateTime();
    }

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

    public function getAssociatedToChannel(): ?Channel
    {
        return $this->associatedToChannel;
    }

    public function setAssociatedToChannel(?Channel $associatedToChannel): static
    {
        $this->associatedToChannel = $associatedToChannel;

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
}
