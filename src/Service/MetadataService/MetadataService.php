<?php

namespace App\Service\MetadataService;

use Doctrine\ORM\EntityManagerInterface;
use Metadata\MetadataFactoryInterface;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactoryInterface;

class MetadataService {
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    public function getMetadata($entity) {
        return (
            is_string($entity)
                ? $this->entityManager->getMetadataFactory()->getMetadataFor($entity)
                : $this->entityManager->getMetadataFactory()->getMetadataFor($entity::class)
        );
    }
}