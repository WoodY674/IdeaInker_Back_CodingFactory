<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 */
class Image
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private ?int $id;
    const ID = "image_id";
    /**
     * @var ?File
     */
    private ?File $imageFile;
    const IMAGE_FILE = "image_file";
    /**
     * @ORM\Column(type="string", length=255)
     */
    private ?string $imageName;
    const IMAGE_NAME = "image_name";
    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private DateTimeImmutable $updatedAt;
    const UPDATED_AT = "updated_at";

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     * @Assert\NotBlank()
     */
    private $imagePath;
    const IMAGE_PATH = "image_path";

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImageFile(): File
    {
        return $this->imageFile;
    }

    /**
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

    public function setImageName(string $imageName): self
    {
        $this->imageName = $imageName;
        $this->setImagePath($imageName);

        return $this;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    public function setImagePath(string $imagePath): self
    {
        $this->imagePath = '/assets/img/posting_image/'.$imagePath;

        return $this;
    }

    public function unsetImageFile() {
        unset($this->imageFile);
    }
}
