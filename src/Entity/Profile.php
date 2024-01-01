<?php

namespace App\Entity;

use App\Repository\ProfileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ProfileRepository::class)]
class Profile
{
    #[Groups(['forIndexingProfile',
        "forPrivateConversation",
        "forGroupCreation",
        "forGroupIndexing",
        "forGroupShowing",
        "forImageIndexing",
        "forShowingPrivateMessage",
        "forChannel",
        "forChannelMessages",

        ])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(["forIndexingProfile",
        "forRequest",
        "forPrivateConversation",
        "forChannel",
        "forChannelMessages",

        ])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[Groups(["forIndexingProfile",
        "forRequest",
        "forPrivateConversation",
        "forChannel",
        "forChannelMessages"
    ])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastName = null;


    #[Groups(["forRequest",
        "forPrivateConversation",
        "forGroupCreation",
        "forGroupIndexing",
        "forChannelMessages",
        "forChannel"
        ])]
    #[ORM\OneToOne(mappedBy: 'profile', cascade: ['persist', 'remove'])]
    private ?User $relatedTo = null;

    #[Groups('forIndexingProfile')]
    #[ORM\OneToMany(mappedBy: 'recipient', targetEntity: Request::class, orphanRemoval: true)]
    private Collection $requests;

    #[Groups('forIndexingProfile')]
    #[ORM\Column]
    private ?bool $visibility = null;

    #[Groups('forIndexingProfile')]
    #[ORM\OneToMany(mappedBy: 'userA', targetEntity: Relation::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $relations;

    #[ORM\OneToMany(mappedBy: 'author', targetEntity: PrivateMessage::class, orphanRemoval: true)]
    private Collection $privatesMessagesFromUser;

    #[ORM\OneToMany(mappedBy: 'relatedToProfileA', targetEntity: PrivateConversation::class, orphanRemoval: true)]
    private Collection $privateConversationsA;

    #[ORM\OneToMany(mappedBy: 'relatedToProfileB', targetEntity: PrivateConversation::class, orphanRemoval: true)]
    private Collection $privateConversationsB;

    #[ORM\ManyToMany(targetEntity: Channel::class, mappedBy: 'channelMembers')]
    private Collection $channels;

    #[ORM\OneToMany(mappedBy: 'uploadedBy', targetEntity: Image::class)]
    private Collection $images;


    public function __construct()
    {
        $this->profiles = new ArrayCollection();
        $this->requests = new ArrayCollection();
        $this->relations = new ArrayCollection();
        $this->privatesMessagesFromUser = new ArrayCollection();
        $this->privateConversationsA = new ArrayCollection();
        $this->privateConversationsB = new ArrayCollection();
        $this->channels = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getRelatedTo(): ?User
    {
        return $this->relatedTo;
    }

    public function setRelatedTo(User $relatedTo): static
    {
        // set the owning side of the relation if necessary
        if ($relatedTo->getProfile() !== $this) {
            $relatedTo->setProfile($this);
        }

        $this->relatedTo = $relatedTo;

        return $this;
    }

    /**
     * @return Collection<int, Request>
     */
    public function getRequests(): Collection
    {
        return $this->requests;
    }

    public function addRequest(Request $request): static
    {
        if (!$this->requests->contains($request)) {
            $this->requests->add($request);
            $request->setRecipient($this);
        }

        return $this;
    }

    public function removeRequest(Request $request): static
    {
        if ($this->requests->removeElement($request)) {
            // set the owning side to null (unless already changed)
            if ($request->getRecipient() === $this) {
                $request->setRecipient(null);
            }
        }

        return $this;
    }

    public function isVisibility(): ?bool
    {
        return $this->visibility;
    }

    public function setVisibility(bool $visibility): static
    {
        $this->visibility = $visibility;

        return $this;
    }

    /**
     * @return Collection<int, Relation>
     */
    public function getRelations(): Collection
    {
        return $this->relations;
    }

    public function addRelation(Relation $relation): static
    {
        if (!$this->relations->contains($relation)) {
            $this->relations->add($relation);
            $relation->setUserA($this);
        }

        return $this;
    }

    public function removeRelation(Relation $relation): static
    {
        if ($this->relations->removeElement($relation)) {
            // set the owning side to null (unless already changed)
            if ($relation->getUserA() === $this) {
                $relation->setUserA(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PrivateMessage>
     */
    public function getPrivatesMessagesFromUser(): Collection
    {
        return $this->privatesMessagesFromUser;
    }

    public function addPrivatesMessagesFromUser(PrivateMessage $privatesMessagesFromUser): static
    {
        if (!$this->privatesMessagesFromUser->contains($privatesMessagesFromUser)) {
            $this->privatesMessagesFromUser->add($privatesMessagesFromUser);
            $privatesMessagesFromUser->setAuthor($this);
        }

        return $this;
    }

    public function removePrivatesMessagesFromUser(PrivateMessage $privatesMessagesFromUser): static
    {
        if ($this->privatesMessagesFromUser->removeElement($privatesMessagesFromUser)) {
            // set the owning side to null (unless already changed)
            if ($privatesMessagesFromUser->getAuthor() === $this) {
                $privatesMessagesFromUser->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PrivateConversation>
     */
    public function getPrivateConversationsA(): Collection
    {
        return $this->privateConversationsA;
    }

    public function addPrivateConversationsA(PrivateConversation $privateConversationsA): static
    {
        if (!$this->privateConversationsA->contains($privateConversationsA)) {
            $this->privateConversationsA->add($privateConversationsA);
            $privateConversationsA->setRelatedToProfileA($this);
        }

        return $this;
    }

    public function removePrivateConversationsA(PrivateConversation $privateConversationsA): static
    {
        if ($this->privateConversationsA->removeElement($privateConversationsA)) {
            // set the owning side to null (unless already changed)
            if ($privateConversationsA->getRelatedToProfileA() === $this) {
                $privateConversationsA->setRelatedToProfileA(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PrivateConversation>
     */
    public function getPrivateConversationsB(): Collection
    {
        return $this->privateConversationsB;
    }

    public function addPrivateConversationsB(PrivateConversation $privateConversationsB): static
    {
        if (!$this->privateConversationsB->contains($privateConversationsB)) {
            $this->privateConversationsB->add($privateConversationsB);
            $privateConversationsB->setRelatedToProfileB($this);
        }

        return $this;
    }

    public function removePrivateConversationsB(PrivateConversation $privateConversationsB): static
    {
        if ($this->privateConversationsB->removeElement($privateConversationsB)) {
            // set the owning side to null (unless already changed)
            if ($privateConversationsB->getRelatedToProfileB() === $this) {
                $privateConversationsB->setRelatedToProfileB(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Channel>
     */
    public function getChannels(): Collection
    {
        return $this->channels;
    }

    public function addChannel(Channel $channel): static
    {
        if (!$this->channels->contains($channel)) {
            $this->channels->add($channel);
            $channel->addChannelMember($this);
        }

        return $this;
    }

    public function removeChannel(Channel $channel): static
    {
        if ($this->channels->removeElement($channel)) {
            $channel->removeChannelMember($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setUploadedBy($this);
        }

        return $this;
    }

    public function removeImage(Image $image): static
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getUploadedBy() === $this) {
                $image->setUploadedBy(null);
            }
        }

        return $this;
    }

}