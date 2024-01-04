<?php

namespace App\Entity;

use App\Repository\RelationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RelationRepository::class)]
class Relation
{
    #[Groups(['forIndexingProfile',"forRelationIndexing"])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['forIndexingProfile',"forRelationIndexing"])]

    #[ORM\ManyToOne(cascade: ["persist"], inversedBy: 'relations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Profile $userA = null;

    #[Groups(['forIndexingProfile',"forRelationIndexing"])]
    #[ORM\ManyToOne(cascade: ["persist"], inversedBy: 'relations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Profile $userB = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserA(): ?Profile
    {
        return $this->userA;
    }

    public function setUserA(?Profile $userA): static
    {
        $this->userA = $userA;

        return $this;
    }

    public function getUserB(): ?Profile
    {
        return $this->userB;
    }

    public function setUserB(?Profile $userB): static
    {
        $this->userB = $userB;

        return $this;
    }
}
