<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 */
#[ApiResource(
    itemOperations: [
        "get" => [
            "security" => "is_granted('READ', object)",
            "security_message" => "Only auth user can access at this image.",
        ],
        "put" => [
            "security" => "is_granted('EDIT', object)",
            "security_message" => "Sorry, but you are not the image owner.",
        ],
        "delete" => [
            "security" => "is_granted('DELETE', object)",
            "security_message" => "Sorry, but you are not the image owner.",
        ],
    ],
)
]
class Image
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
    private $image;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }
}
