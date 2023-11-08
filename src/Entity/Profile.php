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
    #[Groups('forIndexingProfile')]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups('forIndexingProfile')]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[Groups('forIndexingProfile')]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastName = null;

    #[Groups('forIndexingProfile')]
    #[ORM\ManyToMany(targetEntity: self::class, inversedBy: 'profiles')]
    private Collection $friends;

    #[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'friends')]
    private Collection $profiles;

    #[ORM\OneToOne(mappedBy: 'profile', cascade: ['persist', 'remove'])]
    private ?User $relatedTo = null;

    #[Groups('forCreation')]
    #[ORM\OneToMany(mappedBy: 'recipient', targetEntity: Request::class, orphanRemoval: true)]
    private Collection $requests;

    public function __construct()
    {
        $this->friends = new ArrayCollection();
        $this->profiles = new ArrayCollection();
        $this->requests = new ArrayCollection();
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

    /**
     * @return Collection<int, self>
     */
    public function getFriends(): Collection
    {
        return $this->friends;
    }

    public function addFriend(self $friend): static
    {
        if (!$this->friends->contains($friend)) {
            $this->friends->add($friend);
        }

        return $this;
    }

    public function removeFriend(self $friend): static
    {
        $this->friends->removeElement($friend);

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getProfiles(): Collection
    {
        return $this->profiles;
    }

    public function addProfile(self $profile): static
    {
        if (!$this->profiles->contains($profile)) {
            $this->profiles->add($profile);
            $profile->addFriend($this);
        }

        return $this;
    }

    public function removeProfile(self $profile): static
    {
        if ($this->profiles->removeElement($profile)) {
            $profile->removeFriend($this);
        }

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
}
