<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\EmptyController;
use App\Controller\ImageController;
use App\Repository\PostRepository;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Length;

/**
 * @ORM\Entity(repositoryClass=PostRepository::class)
 * @Vich\Uploadable
 */
#[ApiResource(
    collectionOperations: [
        "get",
        "post" => [
            "controller" => EmptyController::class,
            "deserialize" => false,
            "openapi_context" => [
                "requestBody" => [
                    "content" => [
                        "multipart/form-data" => [
                            "schema" => [
                                "type" => "object",
                                "properties" => [
                                    "file" => [
                                        "type" => "string",
                                        "format" => "binary"
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ]
        ]
    ],
    itemOperations: [
        "get" => [
            'normalization_context' => ['groups' => ['read:Post:collection', 'read:Post:item', 'read:Post:User']],
            //"security" => "is_granted('READ', object)",
            //"security_message" => "Only auth user can access at this post.",
        ],
        "put" => [
            //"security" => "is_granted('EDIT', object)",
            //"security_message" => "Sorry, but you are not the post owner.",
        ],
        "delete" => [
            //"security" => "is_granted('DELETE', object)",
            //"security_message" => "Sorry, but you are not the post owner.",
        ],
    ],
    denormalizationContext: [
        ['groups' => ['write:Post']],
    ],
    normalizationContext: [
        'groups' => ['read:Post:collection']
    ],
    paginationItemsPerPage: 10,
    paginationMaximumItemsPerPage: 10
)
]
class Post
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    #[Groups(['read:Post:collection'])]
    private $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    #[
        Groups(['read:Post:collection', 'write:Post']),
        Length(min: 3)
    ]
    private $content;

    /**
     * @ORM\Column(type="datetime_immutable")
     * @Gedmo\Timestampable(on="create")
     */
    #[Groups(['read:Post:collection'])]
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     * @Gedmo\Timestampable(on="update")
     */
    #[Groups(['read:Post:item'])]
    private $updateAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $deletedAt;

    /**
     * @ORM\ManyToOne(targetEntity=Image::class)
     * @ORM\JoinColumn(nullable=false)
     */
    #[Groups(['read:Post:collection', 'write:Post'])]
    private $image;

    /**
     * @var string|null
     */
    #[Groups(['read:Post:collection'])]
    private ?string $imagePath;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="posts")
     * @ORM\JoinColumn(nullable=false)
     */
    #[Groups(['read:Post:collection'])]
    private $createdBy;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

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

    public function getUpdateAt(): ?\DateTimeImmutable
    {
        return $this->updateAt;
    }

    public function setUpdateAt(?\DateTimeImmutable $updateAt): self
    {
        $this->updateAt = $updateAt;

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

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getImagePath(): ?string
    {
        return $this->imagePath;
    }

    /**
     * @param string|null $imagePath
     */
    public function setImagePath(?string $imagePath): void
    {
        $this->imagePath = $imagePath;
    }
}
