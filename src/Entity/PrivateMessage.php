<?php

namespace App\Entity;

use App\Repository\PrivateMessageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PrivateMessageRepository::class)]
class PrivateMessage
{
    #[Groups(["forPrivateConversation"])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(["forPrivateConversation"])]
    #[ORM\ManyToOne(inversedBy: 'privatesMessagesFromUser')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Profile $author = null;

    #[Groups(["forPrivateConversation"])]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'privateMessages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PrivateConversation $associatedToConversation = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\OneToMany(mappedBy: 'relatedToPrivateMessage', targetEntity: PrivateMessageResponse::class, orphanRemoval: true)]
    private Collection $privateMessageResponses;

    public function __construct()
    {
        $this->date = new \DateTime();
        $this->privateMessageResponses = new ArrayCollection();
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

    public function getAssociatedToConversation(): ?PrivateConversation
    {
        return $this->associatedToConversation;
    }

    public function setAssociatedToConversation(?PrivateConversation $associatedToConversation): static
    {
        $this->associatedToConversation = $associatedToConversation;

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

    /**
     * @return Collection<int, PrivateMessageResponse>
     */
    public function getPrivateMessageResponses(): Collection
    {
        return $this->privateMessageResponses;
    }

    public function addPrivateMessageResponse(PrivateMessageResponse $privateMessageResponse): static
    {
        if (!$this->privateMessageResponses->contains($privateMessageResponse)) {
            $this->privateMessageResponses->add($privateMessageResponse);
            $privateMessageResponse->setRelatedToPrivateMessage($this);
        }

        return $this;
    }

    public function removePrivateMessageResponse(PrivateMessageResponse $privateMessageResponse): static
    {
        if ($this->privateMessageResponses->removeElement($privateMessageResponse)) {
            // set the owning side to null (unless already changed)
            if ($privateMessageResponse->getRelatedToPrivateMessage() === $this) {
                $privateMessageResponse->setRelatedToPrivateMessage(null);
            }
        }

        return $this;
    }
}
