<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\SalonRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Valid;

/**
 * @ORM\Entity(repositoryClass=SalonRepository::class)
 */
#[ApiResource(
    collectionOperations: [
        "get",
        "post"
    ],
    itemOperations: [
        "get" => [
            'normalization_context' => ['groups' => ['read:Salon:collection', 'read:Salon:item', 'read:Salon:User']],
            //"security" => "is_granted('READ', object)",
            //"security_message" => "Only auth user can access at this salon.",
        ],
        "put" => [
            //"security" => "is_granted('EDIT', object)",
            //"security_message" => "Sorry, but you are not the salon owner.",
        ],
        "delete" => [
            //"security" => "is_granted('DELETE', object)",
            //"security_message" => "Sorry, but you are not the salon owner.",
        ],
    ],
    denormalizationContext: [
        ['groups' => ['write:Salon']],
    ],
    normalizationContext: [
        'groups' => ['read:Salon:collection']
    ],

)
]
class Salon
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(['read:Salon:collection'])]
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[
        Groups(['read:Salon:collection', 'write:Salon']),
        Length(min: 3)
    ]
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['read:Salon:collection', 'write:Salon'])]
    private $address;

    /**
     * @ORM\Column(type="string", length=15)
     */
    #[Groups(['read:Salon:collection', 'write:Salon'])]
    private $zipCode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[Groups(['read:Salon:collection', 'write:Salon'])]
    private $city;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="salos")
     * @ORM\JoinColumn(nullable=true)
     */
    #[Groups(['read:Salon:collection', 'write:Salon'])]
    private $manager;

    /**
     * @ORM\OneToOne(targetEntity=Image::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=true)
     */
    #[Groups(['read:Salon:collection', 'write:Salon'])]
    private $salonImage;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Gedmo\Timestampable(on="create")
     */
    #[Groups(['read:Salon:collection'])]
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $deletedAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[
        Groups(['read:Salon:collection', 'write:Salon'])
    ]
    private $latitude;

    /**
     * @ORM\Column(type="string", length=255)
     */
    #[
        Groups(['read:Salon:collection', 'write:Salon'])
    ]
    private $longitude;



    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
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
