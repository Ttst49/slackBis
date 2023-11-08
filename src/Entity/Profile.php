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
    #[Groups(['forIndexingProfile'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(["forIndexingProfile","forRequest"])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name = null;

    #[Groups(["forIndexingProfile","forRequest"])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $lastName = null;


    #[ORM\ManyToMany(targetEntity: self::class, mappedBy: 'friends')]
    private Collection $profiles;

    #[Groups(["forRequest"])]
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

    public function __construct()
    {
        $this->profiles = new ArrayCollection();
        $this->requests = new ArrayCollection();
        $this->relations = new ArrayCollection();
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
    public function getProfiles(): Collection
    {
        return $this->profiles;
    }

    public function addProfile(self $profile): static
    {
        if (!$this->profiles->contains($profile)) {
            $this->profiles->add($profile);
        }

        return $this;
    }

    public function removeProfile(self $profile): static
    {
        $this->profiles->removeElement($profile);
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
}
