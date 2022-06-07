<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\NoticeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NoticeRepository::class)
 */
#[ApiResource]
class Notice
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $stars;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $userNoted;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $userNoting;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStars(): ?float
    {
        return $this->stars;
    }

    public function setStars(float $stars): self
    {
        $this->stars = $stars;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getUserNoted(): ?User
    {
        return $this->userNoted;
    }

    public function setUserNoted(?User $userNoted): self
    {
        $this->userNoted = $userNoted;

        return $this;
    }

    public function getUserNoting(): ?User
    {
        return $this->userNoting;
    }

    public function setUserNoting(?User $userNoting): self
    {
        $this->userNoting = $userNoting;

        return $this;
    }
}
