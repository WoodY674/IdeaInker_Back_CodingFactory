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
                dd('is empty');
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
            }
        } else {
            $this->entityManager->persist($images);
        }
    }
    // endregion
}
