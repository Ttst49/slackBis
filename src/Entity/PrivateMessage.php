<?php

namespace App\Entity;

use App\Repository\PrivateMessageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ORM\Entity(repositoryClass: PrivateMessageRepository::class)]
class PrivateMessage
{
    #[Groups(["forPrivateConversation"])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(["forPrivateConversation"])]
    #[ORM\ManyToOne(inversedBy: 'privatesMessagesFromUser')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Profile $author = null;

    #[Groups(["forPrivateConversation"])]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'privateMessages')]
    #[ORM\JoinColumn(nullable: false)]
    private ?PrivateConversation $associatedToConversation = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[Groups(["forPrivateConversation"])]
    #[ORM\OneToMany(mappedBy: 'relatedToPrivateMessage', targetEntity: PrivateMessageResponse::class, orphanRemoval: true)]
    private Collection $privateMessageResponses;

    #[Groups(["forPrivateConversation"])]
    #[ORM\OneToMany(mappedBy: 'privateMessage', targetEntity: Image::class, orphanRemoval: true)]
    #[ORM\JoinColumn(nullable: true)]
    private Collection $images;

    #[SerializedName("images")]
    private ArrayCollection $imagesUrls;

    private ?Array $associatedImages = null;

    public function __construct()
    {
        $this->date = new \DateTime();
        $this->privateMessageResponses = new ArrayCollection();
        $this->images = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): ?Profile
    {
        return $this->author;
    }

    public function setAuthor(?Profile $author): static
    {
        $this->author = $author;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getAssociatedToConversation(): ?PrivateConversation
    {
        return $this->associatedToConversation;
    }

    public function setAssociatedToConversation(?PrivateConversation $associatedToConversation): static
    {
        $this->associatedToConversation = $associatedToConversation;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection<int, PrivateMessageResponse>
     */
    public function getPrivateMessageResponses(): Collection
    {
        return $this->privateMessageResponses;
    }

    public function addPrivateMessageResponse(PrivateMessageResponse $privateMessageResponse): static
    {
        if (!$this->privateMessageResponses->contains($privateMessageResponse)) {
            $this->privateMessageResponses->add($privateMessageResponse);
            $privateMessageResponse->setRelatedToPrivateMessage($this);
        }

        return $this;
    }

    public function removePrivateMessageResponse(PrivateMessageResponse $privateMessageResponse): static
    {
        if ($this->privateMessageResponses->removeElement($privateMessageResponse)) {
            // set the owning side to null (unless already changed)
            if ($privateMessageResponse->getRelatedToPrivateMessage() === $this) {
                $privateMessageResponse->setRelatedToPrivateMessage(null);
            }
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
            $image->setPrivateMessage($this);
        }

        return $this;
    }

    public function removeImage(Image $image): static
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getPrivateMessage() === $this) {
                $image->setPrivateMessage(null);
            }
        }

        return $this;
    }

    public function getAssociatedImages(): ?array
    {
        return $this->associatedImages;
    }

    public function setAssociatedImages(?array $associatedImages): void
    {
        $this->associatedImages = $associatedImages;
    }

    public function getImagesUrls(): ArrayCollection
    {
        return $this->imagesUrls;
    }

    public function setImagesUrls(ArrayCollection $imagesUrls): void
    {
        $this->imagesUrls = $imagesUrls;
    }
}
