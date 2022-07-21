<?php

namespace App\Service\ApiService;

use App\Entity\Image;
use App\Service\ImageService\ImageCreatorService;
use App\Service\MetadataService\MetadataService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ApiConstructorService
{
    private ImageCreatorService $imageCreatorService;
    private MetadataService $metadataService;
    private EntityManagerInterface $entityManager;
    private FillEntity $fillEntity;

    public function __construct(ImageCreatorService $imageCreatorService, EntityManagerInterface $entityManager,
                                MetadataService $metadataService, FillEntity $fillEntity)
    {
        $this->imageCreatorService = $imageCreatorService;
        $this->entityManager = $entityManager;
        $this->metadataService = $metadataService;
        $this->fillEntity = $fillEntity;
    }
    // region Serializer

    // for VisualStudio style
    // <editor-fold desc="Api"></editor-fold> for NetBeans-like style

    public function getSerializer(): Serializer
    {
        // On spécifie qu'on utilise l'encodeur JSON
        $encoders = [new JsonEncoder()];

        // On instancie le "normaliseur" pour convertir la collection en tableau
        $normalizers = [new ObjectNormalizer()];

        // On instancie le convertisseur
        return new Serializer($normalizers, $encoders);
    }
    // endregion

    // region API Send Data

    public function getRawJson(mixed $data): string
    {
        // On récupère le serializer déjà configurer par défaut
        $serializer = $this->getSerializer();
        // On convertit en json
        return $serializer->serialize($data, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            },
        ]);
    }

    /**
     * @param bool $defaultIsJson /!\ if is false you need to add a content-type, form-data ect ect
     *
     * @return Response
     */
    public function initReponse(string $json = '', array $customHeader = null, bool $defaultIsJson = true)
    {
        // if you have data, set the response with data or init by default by empty string
        $response = new Response($json);

        if (!empty($customHeader)) {
            // Set multiple headers simultaneously
            $response->headers->add($customHeader);
        }

        if ($defaultIsJson) {
            $response->headers->set('Content-Type', 'application/json');
        }

        return $response;
    }

    public function getResponseForApi(mixed $data, array $customHeader = null, bool $defaultIsJson = true): Response
    {
        return $this->initReponse($this->getRawJson($data), $customHeader, $defaultIsJson);
    }
    // endregion

    // region API Get Data

    /**
     * GET DATA FROM A JSON OR FORM-DATA.
     */
    public function getJsonBodyFromRequest(Request $request, mixed $targetEntity = null): mixed
    {
        $uploadApiModel = null;
        if (str_contains($request->headers->get('Content-Type'), 'application/json')) {
            if (isset($targetEntity)) {
                // mapping data with json Whith METADATA
                $data = json_decode($request->getContent(), true);
                $uploadApiModel = $this->mappingRelation($targetEntity, $data);

                return $uploadApiModel;
            } else {
                return json_decode($request->getContent(), true);
                // ToDo: aucune entity à été renseigné donc l'entity ne se remplira pas automatiquement
            }
        } else {
            dd($uploadApiModel);
        }

        return json_decode($request->getContent(), true);
    }

    /**
     * Get metadata for relation and field
     * if its a relation with image decode base64 else find the entity.
     *
     * @param $entity
     * @param $data
     *
     * @return mixed
     */
    private function mappingRelation($entity, $data)
    {
        $metadata = $this->metadataService->getMetadata($entity);
        $associationInput = $metadata->getAssociationMappings();

        foreach ($data as $key => $value) {
            if (key_exists($key, $associationInput)) {
                if (Image::class === $associationInput[$key]['targetEntity']) {
                    $data[$key] = $this->extractFile64($value);
                } else {
                    $relationEntity = $this->entityManager->getRepository($associationInput[$key]['targetEntity'])->findOneBy(['id' => $value]);
                    if (!isset($relationEntity)) {
                        continue;
                    }
                    $data[$key] = $relationEntity;
                }
            }
        }

        return $this->fillEntity->fillEntity($entity, $data);
    }

    /**
     * @param $data
     *
     * @return Image|array|null
     */
    private function extractFile64($data)
    {
        $images = $this->imageCreatorService->convertImages64ToEntity($data);
        $this->persistImage($images);

        return $images;
    }

    /**
     * @param $images
     *
     * @return void
     */
    private function persistImage($images)
    {
        if (is_array($images)) {
            foreach ($images as $image) {
                $this->entityManager->persist($image);
                $image->unsetImageFile();
            }
        } else {
            $this->entityManager->persist($images);
            $images->unsetImageFile();
        }
    }
    // endregion
    /** par X raison impossible de prendre les metadatas obliger de les renseingner via un controller */
    public function getSimpleDataFromEntity($entity, $metadata) {
        if (is_array($entity) || gettype($entity) === 'object' && $entity::class === 'Doctrine\ORM\PersistentCollection') {
            $data = [];
            foreach ($entity as $value) {
                $data[] = $this->getSimpleData($value, $metadata);
            }
            return $data;
        } else {
            return $this->getSimpleData($entity, $metadata);
        }
    }

    private function getSimpleData($entity, $metadata) {
        $fields = $metadata->fieldMappings;
        $data = [];
        foreach ($fields as $key => $value) {
            $methods = $this->createMethods($value['fieldName']);
            if($value['type'] === 'datetime_immutable') {
                $date = $entity->$methods();
                if($date !== null) {
                    $data[$key] = $date->format('d-m-Y');
                }
            } else {
                $data[$key] = $entity->$methods();
            }
        }

        return $data;
    }

    private function createMethods($name) {
        return 'get' . ucfirst($name);
    }

    public function getGetFunctionFromJsonKey(array $jsonKey, string $classEntity){
        $listOfMethods = [];
        $allMethodInEntity = get_class_methods($classEntity);
        foreach ($jsonKey as $key => $jsonKey) {
            $method = 'get' . ucfirst($this->replaceKeyJsonByProperties($jsonKey));
            if (in_array($method, $allMethodInEntity)) {
                $listOfMethods[$key] = $method;
            }
        }
        return $listOfMethods;
    }

    public function getSetFunctionFromJsonKey(array $jsonKeys, string $classEntity){
        $listOfMethods = [];
        $allMethodInEntity = get_class_methods($classEntity);
        foreach ($jsonKeys as $jsonKey) {
            $method = 'set' . ucfirst($this->replaceKeyJsonByProperties($jsonKey));
            if (in_array($method, $allMethodInEntity)) {
                $listOfMethods[$jsonKey] = $method;
            }
        }
        return $listOfMethods;
    }

    public function replaceKeyJsonByProperties($keyJson) {
        $partOfStrings = explode("_", $keyJson);
        if(count($partOfStrings) < 1) {
            return $keyJson;
        } else {
            $nameProperties = "";
            for ($i = 0; $i < count($partOfStrings); ++$i) {
                if($i === 0) {
                    $nameProperties .= $partOfStrings[$i];
                } else {
                    $nameProperties .= ucfirst($partOfStrings[$i]);

                }
            }
            return $nameProperties;
        }
    }
}
