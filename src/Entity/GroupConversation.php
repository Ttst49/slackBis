<?php

namespace App\Entity;

use App\Repository\GroupConversationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: GroupConversationRepository::class)]
class GroupConversation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(["forGroupCreation"])]
    #[ORM\OneToMany(mappedBy: 'groupConversation', targetEntity: GroupMessage::class, orphanRemoval: true)]
    private Collection $groupMessages;

    #[Groups(["forGroupCreation"])]
    #[ORM\ManyToMany(targetEntity: Profile::class)]
    private Collection $groupMembers;

    #[Groups(["forGroupCreation"])]
    #[ORM\ManyToMany(targetEntity: User::class)]
    private Collection $adminMembers;

    #[Groups(["forGroupCreation"])]
    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Profile $owner = null;


    public function __construct()
    {
        $this->groupMessages = new ArrayCollection();
        $this->groupMembers = new ArrayCollection();
        $this->adminMembers = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, GroupMessage>
     */
    public function getGroupMessages(): Collection
    {
        return $this->groupMessages;
    }

    public function addGroupMessage(GroupMessage $groupMessage): static
    {
        if (!$this->groupMessages->contains($groupMessage)) {
            $this->groupMessages->add($groupMessage);
            $groupMessage->setGroupConversation($this);
        }

        return $this;
    }

    public function removeGroupMessage(GroupMessage $groupMessage): static
    {
        if ($this->groupMessages->removeElement($groupMessage)) {
            // set the owning side to null (unless already changed)
            if ($groupMessage->getGroupConversation() === $this) {
                $groupMessage->setGroupConversation(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Profile>
     */
    public function getGroupMembers(): Collection
    {
        return $this->groupMembers;
    }

    public function addGroupMember(Profile $membersFromGroup): static
    {
        if (!$this->groupMembers->contains($membersFromGroup)) {
            $this->groupMembers->add($membersFromGroup);
        }

        return $this;
    }

    public function removeGroupMember(Profile $membersFromGroup): static
    {
        $this->groupMembers->removeElement($membersFromGroup);

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getAdminMembers(): Collection
    {
        return $this->adminMembers;
    }

    public function addAdminMember(User $adminMember): static
    {
        if (!$this->adminMembers->contains($adminMember)) {
            $this->adminMembers->add($adminMember);
        }

        return $this;
    }

    public function removeAdminMember(User $adminMember): static
    {
        $this->adminMembers->removeElement($adminMember);

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


}
