<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

#[ApiResource(
    collectionOperations: ['get', 'post'],
    itemOperations: [
        'get',
        'put' => [
            'denormilization_context' => [
                'groups' => ['put:Dependency'],
            ],
        ],
        'delete',
    ],
    paginationEnabled: false
)]
class Dependency
{
    #[ApiProperty(
        identifier: true
    )]
    private string $uuid;

    #[
        ApiProperty(
            description: 'Nom de la dépendance'
        ),
        Length(min: 2),
        NotBlank()
    ]
    private string $name;

    #[
        ApiProperty(
            description: 'Version de la dépendance',
            openapiContext: [
                'example' => '5.2.*',
            ],
        ),
        Length(min: 2),
        NotBlank(),
        Groups(['put:Dependency'])
    ]
    private string $version;

    public function __construct(string $name, string $version)
    {
        $this->uuid = Uuid::uuid5(Uuid::NAMESPACE_URL, $name)->toString();
        $this->name = $name;
        $this->version = $version;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function setVersion(string $version): void
    {
        $this->version = $version;
    }
}
