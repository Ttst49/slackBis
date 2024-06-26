<?php

namespace App\Entity;

use App\Repository\PrivateConversationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: PrivateConversationRepository::class)]
class PrivateConversation
{
    #[Groups("forPrivateConversation")]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(["forPrivateConversation","forShowingPrivateMessage"])]
    #[ORM\OneToMany(mappedBy: 'associatedToConversation', targetEntity: PrivateMessage::class, orphanRemoval: true)]
    private Collection $privateMessages;

    #[Groups("forPrivateConversation")]
    #[ORM\ManyToOne(inversedBy: 'privateConversationsA')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Profile $relatedToProfileA = null;

    #[Groups("forPrivateConversation")]
    #[ORM\ManyToOne(inversedBy: 'privateConversationsB')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Profile $relatedToProfileB = null;

    public function __construct()
    {
        $this->privateMessages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, PrivateMessage>
     */
    public function getPrivateMessages(): Collection
    {
        return $this->privateMessages;
    }

    public function addPrivateMessage(PrivateMessage $privateMessage): static
    {
        if (!$this->privateMessages->contains($privateMessage)) {
            $this->privateMessages->add($privateMessage);
            $privateMessage->setAssociatedToConversation($this);
        }

        return $this;
    }

    public function removePrivateMessage(PrivateMessage $privateMessage): static
    {
        if ($this->privateMessages->removeElement($privateMessage)) {
            // set the owning side to null (unless already changed)
            if ($privateMessage->getAssociatedToConversation() === $this) {
                $privateMessage->setAssociatedToConversation(null);
            }
        }

        return $this;
    }

    public function getRelatedToProfileA(): ?Profile
    {
        return $this->relatedToProfileA;
    }

    public function setRelatedToProfileA(?Profile $relatedToProfileA): static
    {
        $this->relatedToProfileA = $relatedToProfileA;

        return $this;
    }

    public function getRelatedToProfileB(): ?Profile
    {
        return $this->relatedToProfileB;
    }

    public function setRelatedToProfileB(?Profile $relatedToProfileB): static
    {
        $this->relatedToProfileB = $relatedToProfileB;

        return $this;
    }
}
