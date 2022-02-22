<?php

namespace App\Service\ApiService;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class ApiConstructorService {

    // region Serializer

    // for VisualStudio style
    // <editor-fold desc="Api"></editor-fold> for NetBeans-like style

    /**
     * @return Serializer
     */
    public function getSerializer(): Serializer {
        // On spécifie qu'on utilise l'encodeur JSON
        $encoders = [new JsonEncoder()];

        // On instancie le "normaliseur" pour convertir la collection en tableau
        $normalizers = [new ObjectNormalizer()];

        // On instancie le convertisseur
        return new Serializer($normalizers, $encoders);
    }
    //endregion

    // region API Send Data

    /**
     * @param mixed $data
     * @return String
     */
    public function getRawJson(mixed $data): String {
        // On récupère le serializer déjà configurer par défaut
        $serializer = $this->getSerializer();
        // On convertit en json
        return $serializer->serialize($data, 'json', [
            'circular_reference_handler' => function ($object) {
                return $object->getId();
            }
        ]);
    }

    /**
     * @param String $json
     * @param array|null $customHeader
     * @param bool $defaultIsJson /!\ if is false you need to add a content-type, form-data ect ect
     * @return Response
     */
    public function initReponse(String $json = '', array $customHeader = null, bool $defaultIsJson = true) {
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

    /**
     * @param mixed $data
     * @param array|null $customHeader
     * @param bool $defaultIsJson
     * @return Response
     */
    public function getResponseForApi(mixed $data, array $customHeader = null, bool $defaultIsJson = true): Response {
        return $this->initReponse($this->getRawJson($data), $customHeader, $defaultIsJson);
    }
    // endregion

    //region API Get Data

    /**
     * GET DATA FROM A JSON OR FORM-DATA
     *
     * @param Request $request
     * @param mixed|null $targetEntity
     * @return mixed
     */
    public function getJsonBodyFromRequest(Request $request, mixed $targetEntity = null): mixed {
        $uploadApiModel = null;
        if ($request->headers->get('Content-Type') === 'application/json') {
            if (isset($targetEntity)) {
                $uploadApiModel = $this->getSerializer()->deserialize(
                    $request->getContent(),
                    $targetEntity,
                    'json'
                );

                dd($uploadApiModel);
            } else {
                dd("is empty");
                //ToDo: aucune entity à été renseigné donc l'entity ne se remplira pas automatiquement
            }
        } else {
            $data['files'] = $this->getFileInRequest($request);
        }
        dd($uploadApiModel);
        return json_decode($request->getContent(), true);
    }

    private function getFileInRequest(Request $request): array {
        $files = [];

        if (!empty($request->files)) {
            foreach ($request->files as $key => $file) {
                //array_push($data[$key], $file);
                //dd($key);
                //$data[$key] = array_merge_recursive($data, $file);
                $files['files'][$key] = $file;
            }
        }

        return $files;
    }
    // endregion

}