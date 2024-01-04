<?php

namespace App\Entity;

use App\Repository\RequestRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RequestRepository::class)]
class Request
{
    #[Groups(['forIndexingProfile',"forRequest"])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(["forRequest"])]
    #[ORM\ManyToOne(inversedBy: 'requests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Profile $recipient = null;

    #[Groups(["forRequest"])]
    #[ORM\ManyToOne(inversedBy: 'requests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Profile $sender = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRecipient(): ?Profile
    {
        return $this->recipient;
    }

    public function setRecipient(?Profile $recipient): static
    {
        $this->recipient = $recipient;

        return $this;
    }

    public function getSender(): ?Profile
    {
        return $this->sender;
    }

    public function setSender(?Profile $sender): static
    {
        $this->sender = $sender;

        return $this;
    }

}
