<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity]
#[Vich\Uploadable]
class Image
{
    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    #[Groups(["forImageIndexing","forPrivateConversation"])]
    private ?int $id = null;


    #[Vich\UploadableField(mapping: 'images', fileNameProperty: 'imageName', size: 'imageSize')]
    private ?File $imageFile = null;

    #[Groups(["forImageIndexing"])]
    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;

    #[Groups(["forImageIndexing"])]
    #[ORM\Column(nullable: true)]
    private ?int $imageSize = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[Groups(["forImageIndexing"])]
    #[ORM\ManyToOne(inversedBy: 'images')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Profile $uploadedBy = null;

    #[ORM\ManyToOne(inversedBy: 'images')]
    #[ORM\JoinColumn(nullable: true)]
    private ?PrivateMessage $privateMessage = null;


    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageSize(?int $imageSize): void
    {
        $this->imageSize = $imageSize;
    }

    public function getImageSize(): ?int
    {
        return $this->imageSize;
    }

    public function getUploadedBy(): ?Profile
    {
        return $this->uploadedBy;
    }

    public function setUploadedBy(?Profile $uploadedBy): static
    {
        $this->uploadedBy = $uploadedBy;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrivateMessage(): ?PrivateMessage
    {
        return $this->privateMessage;
    }

    public function setPrivateMessage(?PrivateMessage $privateMessage): static
    {
        $this->privateMessage = $privateMessage;

        return $this;
    }
}