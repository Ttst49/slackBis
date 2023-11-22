<?php

namespace App\Entity;

use App\Repository\ChannelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ChannelRepository::class)]
class Channel
{
    #[Groups(["forChannel"])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[Groups(["forChannel"])]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $name = null;

    #[Groups(["forChannel"])]
    #[ORM\OneToMany(mappedBy: 'associatedToChannel', targetEntity: ChannelMessage::class, orphanRemoval: true)]
    private Collection $channelMessages;

    #[Groups(["forChannel"])]
    #[ORM\ManyToMany(targetEntity: Profile::class, inversedBy: 'channels')]
    private Collection $channelMembers;

    #[Groups(["forChannel"])]
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'channels')]
    private Collection $adminChannelMembers;

    #[Groups(["forChannel"])]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Profile $owner = null;

    #[Groups(["forChannel"])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    public function __construct()
    {
        $this->channelMessages = new ArrayCollection();
        $this->channelMembers = new ArrayCollection();
        $this->adminChannelMembers = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->name = "Nom par défaut";
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, ChannelMessage>
     */
    public function getChannelMessages(): Collection
    {
        return $this->channelMessages;
    }

    public function addChannelMessage(ChannelMessage $channelMessage): static
    {
        if (!$this->channelMessages->contains($channelMessage)) {
            $this->channelMessages->add($channelMessage);
            $channelMessage->setAssociatedToChannel($this);
        }

        return $this;
    }

    public function removeChannelMessage(ChannelMessage $channelMessage): static
    {
        if ($this->channelMessages->removeElement($channelMessage)) {
            // set the owning side to null (unless already changed)
            if ($channelMessage->getAssociatedToChannel() === $this) {
                $channelMessage->setAssociatedToChannel(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Profile>
     */
    public function getChannelMembers(): Collection
    {
        return $this->channelMembers;
    }

    public function addChannelMember(Profile $channelMember): static
    {
        if (!$this->channelMembers->contains($channelMember)) {
            $this->channelMembers->add($channelMember);
        }

        return $this;
    }

    public function removeChannelMember(Profile $channelMember): static
    {
        $this->channelMembers->removeElement($channelMember);

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getAdminChannelMembers(): Collection
    {
        return $this->adminChannelMembers;
    }

    public function addAdminChannelMember(User $adminChannelMember): static
    {
        if (!$this->adminChannelMembers->contains($adminChannelMember)) {
            $this->adminChannelMembers->add($adminChannelMember);
        }

        return $this;
    }

    public function removeAdminChannelMember(User $adminChannelMember): static
    {
        $this->adminChannelMembers->removeElement($adminChannelMember);

        return $this;
    }

    public function getOwner(): ?Profile
    {
        return $this->owner;
    }

    public function setOwner(?Profile $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }
}
