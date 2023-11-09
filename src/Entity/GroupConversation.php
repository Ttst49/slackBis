<?php

namespace App\Entity;

use App\Repository\GroupConversationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GroupConversationRepository::class)]
class GroupConversation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'groupConversation', targetEntity: GroupMessage::class, orphanRemoval: true)]
    private Collection $groupMessages;

    #[ORM\ManyToMany(targetEntity: Profile::class)]
    private Collection $membersFromGroup;

    #[ORM\ManyToMany(targetEntity: User::class)]
    private Collection $adminMembers;


    public function __construct()
    {
        $this->groupMessages = new ArrayCollection();
        $this->membersFromGroup = new ArrayCollection();
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
    public function getMembersFromGroup(): Collection
    {
        return $this->membersFromGroup;
    }

    public function addMembersFromGroup(Profile $membersFromGroup): static
    {
        if (!$this->membersFromGroup->contains($membersFromGroup)) {
            $this->membersFromGroup->add($membersFromGroup);
        }

        return $this;
    }

    public function removeMembersFromGroup(Profile $membersFromGroup): static
    {
        $this->membersFromGroup->removeElement($membersFromGroup);

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


}
