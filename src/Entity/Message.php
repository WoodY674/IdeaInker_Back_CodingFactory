<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\MessageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MessageRepository::class)
 */
#[ApiResource(
    collectionOperations: [
        "get",
        "post" => [
            //"security_post_denormalize" => "is_granted('CREATE', object)",
            //"security_message" => "Only auth user can create.",
        ],
    ],
    itemOperations: [
        "get" => [
            //"security" => "is_granted('READ', object)",
            //"security_message" => "Only auth user can access at this message.",
        ],
        "put" => [
            //"security" => "is_granted('EDIT', object)",
            //"security_message" => "Sorry, but you are not the message owner.",
        ],
        "delete" => [
            //"security" => "is_granted('DELETE', object)",
            //"security_message" => "Sorry, but you are not the message owner.",
        ],
    ],
    attributes: ["security" => "is_granted('ROLE_USER')"]
)
]
class Message
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $message;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private $sendAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $reedAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="messages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sendBy;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $recipient;

    /**
     * @ORM\ManyToMany(targetEntity=Image::class)
     */
    private $attachment;

    public function __construct()
    {
        $this->attachment = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function getSendAt(): ?\DateTimeImmutable
    {
        return $this->sendAt;
    }

    public function setSendAt(\DateTimeImmutable $sendAt): self
    {
        $this->sendAt = $sendAt;

        return $this;
    }

    public function getReedAt(): ?\DateTimeImmutable
    {
        return $this->reedAt;
    }

    public function setReedAt(?\DateTimeImmutable $reedAt): self
    {
        $this->reedAt = $reedAt;

        return $this;
    }

    public function getSendBy(): ?User
    {
        return $this->sendBy;
    }

    public function setSendBy(?User $sendBy): self
    {
        $this->sendBy = $sendBy;

        return $this;
    }

    public function getRecipient(): ?User
    {
        return $this->recipient;
    }

    public function setRecipient(?User $recipient): self
    {
        $this->recipient = $recipient;

        return $this;
    }

    /**
     * @return Collection|Image[]
     */
    public function getAttachment(): Collection
    {
        return $this->attachment;
    }

    public function addAttachment(Image $attachment): self
    {
        if (!$this->attachment->contains($attachment)) {
            $this->attachment[] = $attachment;
        }

        return $this;
    }

    public function removeAttachment(Image $attachment): self
    {
        $this->attachment->removeElement($attachment);

        return $this;
    }
}
