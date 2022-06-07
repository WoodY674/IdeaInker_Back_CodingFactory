<?php

namespace App\Service\MetadataService;

use Doctrine\ORM\EntityManagerInterface;

class MetadataService
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * get the metadata for a entity by the object or string.
     *
     * @param $entity
     *
     * @return \Doctrine\Persistence\Mapping\ClassMetadata|mixed|void
     *
     * @throws \Doctrine\Persistence\Mapping\MappingException
     * @throws \ReflectionException
     */
    public function getMetadata($entity)
    {
        return
            is_string($entity)
                ? $this->entityManager->getMetadataFactory()->getMetadataFor($entity)
                : $this->entityManager->getMetadataFactory()->getMetadataFor($entity::class)
        ;
    }
}
