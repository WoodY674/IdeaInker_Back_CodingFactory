<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ChannelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ChannelRepository::class)
 */
#[ApiResource(
    normalizationContext: ['groups' => ['read:Channel:collection']]
)]
class Channel
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(['read:Channel:collection'])]
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=User::class)
     * @Assert\NotBlank()
     */
    #[Groups(['read:Channel:collection'])]
    private $usersInside;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="channel")
     * @Assert\NotBlank()
     */
    #[Groups(['read:Channel:collection'])]
    private $messages;

    public function __construct()
    {
        $this->usersInside = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsersInside(): Collection
    {
        return $this->usersInside;
    }

    public function addUsersInside(User $usersInside): self
    {
        if (!$this->usersInside->contains($usersInside)) {
            $this->usersInside[] = $usersInside;
        }

        return $this;
    }

    public function removeUsersInside(User $usersInside): self
    {
        $this->usersInside->removeElement($usersInside);

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): self
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setChannel($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): self
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getChannel() === $this) {
                $message->setChannel(null);
            }
        }

        return $this;
    }
}

