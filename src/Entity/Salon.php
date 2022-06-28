<?php

namespace App\Entity;

use App\Repository\SalonRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * @ORM\Entity(repositoryClass=SalonRepository::class)
 */
class Salon {


    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    const ID = "salon_id";

    /**
     * @ORM\Column(type="string", length=255)
     *@Assert\NotBlank()
     */
    private $name;
    const NAME = "name";
    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $address;
    const ADDRESS = "address";

    /**
     * @ORM\Column(type="string", length=15)
     * @Assert\NotBlank()
     */
    private $zipCode;
    const ZIPCODE = "zip_code";

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $city;
    const CITY = "city";

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="salos")
     * @ORM\JoinColumn(nullable=true)
     */
    private $manager;
    const MANAGER = "manager";

    /**
     * @ORM\OneToOne(targetEntity=Image::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    private $salonImage;
    const SALON_IMAGE = "salon_image";

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Gedmo\Timestampable(on="create")
     */
    private $createdAt;
    const CREATED_AT = "created_at";

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;
    const UPDATED_AT = "updated_at";

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $deletedAt;
    const DELETED_AT = "deleted_at";

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $latitude;
    const LATITUDE = "latitude";


    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank()
     */
    private $longitude;
    const LONGITUDE = "longitude";

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getZipCode(): ?string
    {
        return $this->zipCode;
    }

    public function setZipCode(string $zipCode): self
    {
        $this->zipCode = $zipCode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getManager(): ?User
    {
        return $this->manager;
    }

    public function setManager(?User $manager): self
    {
        $this->manager = $manager;

        return $this;
    }

    public function getSalonImage(): ?Image
    {
        return $this->salonImage;
    }

    public function setSalonImage(Image $salonImage): self
    {
        $this->salonImage = $salonImage;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?\DateTimeImmutable $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(string $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }
}
