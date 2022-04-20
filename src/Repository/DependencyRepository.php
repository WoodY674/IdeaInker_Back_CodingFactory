<?php

namespace App\Repository;

use App\Entity\Dependency;
use Ramsey\Uuid\Uuid;

class DependencyRepository {

    private string $rootPath;
    private string $path;
    private $json;

    public function __construct(string $rootPath) {
        $this->rootPath = $rootPath;
        $this->path = $rootPath . '/composer.json';
        $this->json = json_decode(file_get_contents($this->path), true);
    }

    public function findAll(): array {
        $items = [];
        foreach ($this->getDependencies() as $name => $version) {
            $items[] = new Dependency($name, $version);
        }
        return $items;
    }

    public function find(string $uuid): ?Dependency {

        foreach($this->findAll() as $dependency) {
            if($dependency->getUuid() === $uuid) {
                return $dependency;
            }
        }
        return null;
    }

    public function persist(Dependency $dependency) {
        $this->json['require'][$dependency->getName()] = $dependency->getVersion();
        $this->writeInFile();
    }

    public function remove(Dependency $dependency) {
        unset($this->json['require'][$dependency->getName()]);
        $this->writeInFile();
    }

    private function getDependencies(): array {
        return $this->json['require'];
    }

    private function writeInFile() {
        file_put_contents($this->path, json_encode($this->json, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}