<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 * @Vich\Uploadable
 */
#[ApiResource(
    itemOperations: [
        "get" => [
            //"security" => "is_granted('READ', object)",
            //"security_message" => "Only auth user can access at this image.",
        ],
        "put" => [
            //"security" => "is_granted('EDIT', object)",
            //"security_message" => "Sorry, but you are not the image owner.",
        ],
        "delete" => [
            //"security" => "is_granted('DELETE', object)",
            //"security_message" => "Sorry, but you are not the image owner.",
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
    private ?int $id;

    /**
     * @var ?File
     * @Vich\UploadableField(mapping="post_image", fileNameProperty="imageName")
     */
    private ?File $imageFile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $imageName;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * @var DateTimeImmutable
     */
    private DateTimeImmutable $updatedAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $imagePath;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return File
     */
    public function getImageFile(): File
    {
        return $this->imageFile;
    }

    /**
     * @param File $imageFile
     * @return Image
     */
    public function setImageFile(File $imageFile): self
    {
        $this->imageFile = $imageFile;

        // if 'updatedAt' is not defined in your entity, use another property
        $this->updatedAt = new DateTimeImmutable('now');

        return $this;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function setImageName(string $imageName): self {
        $this->imageName = $imageName;
        $this->setImagePath($imageName);
        return $this;
    }

    public function getUpdateAt(): DateTimeImmutable {
        return $this->updatedAt;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(string $imagePath): self {
        $this->imagePath = "/assets/img/posting_image/" . $imagePath;
        return $this;
    }

}
