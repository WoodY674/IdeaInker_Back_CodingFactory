<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\CoordinateStoreRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Length;

/**
 * @ORM\Entity(repositoryClass=CoordinateStoreRepository::class)
 */
#[ApiResource]
class CoordinateStore
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
    #[
        Groups(['read:Salon', 'write:Salon']),
        Length(min: 3)

    ]
    private $company;

    #[
        Groups(['read:Salon', 'write:Salon'])
    ]
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    #[
        Groups(['read:Salon', 'write:Salon'])
    ]
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $latitude;

    #[
        Groups(['read:Salon', 'write:Salon'])
    ]
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $longitude;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(string $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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
