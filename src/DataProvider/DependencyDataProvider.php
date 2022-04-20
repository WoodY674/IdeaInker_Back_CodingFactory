<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\Exception\ResourceClassNotSupportedException;
use App\Entity\Dependency;
use App\Repository\DependencyRepository;
use Ramsey\Uuid\Uuid;

class DependencyDataProvider implements CollectionDataProviderInterface, RestrictedDataProviderInterface, ItemDataProviderInterface {

    private DependencyRepository $dependencyRepository;
    public function __construct(DependencyRepository $dependencyRepository) {
        $this->dependencyRepository = $dependencyRepository;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool {
        return $resourceClass === Dependency::class;
    }

    public function getCollection(string $resourceClass, string $operationName = null) {
        return $this->dependencyRepository->findAll();
    }

    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []) {
        return $this->dependencyRepository->find($id);
    }
}