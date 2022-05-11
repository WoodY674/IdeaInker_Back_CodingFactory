<?php

namespace App\Service\ApiService;

use App\Service\MetadataService\MetadataService;

class FillEntity {
    private MetadataService $metadataService;

    public function __construct(MetadataService $metadataService) {
        $this->metadataService = $metadataService;
    }

    public function fillEntity($entity, $data) {
        $metadata = $this->metadataService->getMetadata($entity);
        $entityObj = new $entity;
        foreach ($metadata->reflClass->getMethods() as $method) {

            if (!empty($method->getParameters())) {
                $key = $method->getParameters()[0]->name;
                $methodName = $method->name;
                if (key_exists($method->getParameters()[0]->name, $data)) {
                    $entityObj->$methodName($data[$key]);
                }
            }
        }
        return $entityObj;
    }

}