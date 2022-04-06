<?php

namespace App\DataProvider;

use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use ApiPlatform\Core\Exception\ResourceClassNotSupportedException;
use App\Entity\Dependency;

class DependencyDataProvider implements CollectionDataProviderInterface, RestrictedDataProviderInterface {

    public function __construct() {
    }

    public function getCollection(string $resourceClass, string $operationName = null)
    {
        // TODO: Implement getCollection() method.
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool {
        return $resourceClass === Dependency::class;
    }
}