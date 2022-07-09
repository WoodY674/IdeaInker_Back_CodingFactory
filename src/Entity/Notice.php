<?php

namespace App\Entity;

use App\Repository\NoticeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=NoticeRepository::class)
 */
class Notice
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    const ID = "notice_id";

    /**
     * @ORM\Column(type="float", length=255)
     */
    private $stars;
    const STARS = "stars";
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $comment;
    const COMMENT = "comment";

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $userNoted;
    const USER_NOTED = "user_noted";

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $userNoting;
    const USER_NOTING = "user_noting";

    /**
     * @ORM\ManyToOne(targetEntity=Salon::class, inversedBy="notices")
     */
    private $salonNoted;
    const SALON_NOTED = "salon_noted";

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

    public function getSalonNoted(): ?Salon
    {
        return $this->salonNoted;
    }

    public function setSalonNoted(?Salon $salonNoted): self
    {
        $this->salonNoted = $salonNoted;

        return $this;
    }
}
