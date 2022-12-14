<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MessageRepository::class)
 */
class Message
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    const ID_MESSAGE = "id_message";

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $message;
    const MESSAGE = "message";

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Gedmo\Timestampable(on="create")
     */
    private $sendAt;
    const SEND_AT = "send_at";

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $reedAt;
    const REED_AT = "reed_at";

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="messages")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     */
    private $sendBy;
    const SEND_BY = "send_by";

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank()
     */
    private $recipient;
    const RECIPIENT = "recipient";

    /**
     * @ORM\ManyToMany(targetEntity=Image::class)
     */
    private $attachment;
    const ATTACHMENT = "attachment";

    /**
     * @ORM\ManyToOne(targetEntity=Channel::class, inversedBy="messages")
     * @Assert\NotBlank()
     */
    private $channel;

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

    public function getChannel(): ?Channel
    {
        return $this->channel;
    }

    public function setChannel(?Channel $channel): self
    {
        $this->channel = $channel;

        return $this;
    }
}
