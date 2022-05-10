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
     *
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $idUserNoted;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $userIdNoting;

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

    public function getIdUserNoted(): ?User
    {
        return $this->idUserNoted;
    }

    public function setIdUserNoted(?User $idUserNoted): self
    {
        $this->idUserNoted = $idUserNoted;

        return $this;
    }

    public function getUserIdNoting(): ?User
    {
        return $this->userIdNoting;
    }

    public function setUserIdNoting(?User $userIdNoting): self
    {
        $this->userIdNoting = $userIdNoting;

        return $this;
    }
}
