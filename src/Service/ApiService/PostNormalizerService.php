<?php

namespace App\Service\ApiService;

use App\Entity\Post;
use Symfony\Component\Serializer\Exception\CircularReferenceException;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Vich\UploaderBundle\Storage\StorageInterface;

/**
 * ICI UN NORMALIZER
 * Pour le moment c'est un normalizer que pour un post on pourrait peut-être le généraliser avec une verification des instance
 */
class PostNormalizerService implements ContextAwareNormalizerInterface, NormalizerAwareInterface {

    // permet de ne pas générer les fonctions de NormalizerAwareInterface
    use NormalizerAwareTrait;

    // Permet de ne pas appeller en boucle le normaliser
    private const ALREADY_USE = "NormalizerServiceAlreadyCall";

    // apelle et créer une variable/fonction storage de vich
    public function __construct(private StorageInterface $storage) {
    }

    // vérifie que il n'est déjà pas utiliséet que l'objet est bien une instance de post
    public function supportsNormalization($data, string $format = null, array $context = []) {
        return !isset($context[self::ALREADY_USE]) && $data instanceof Post;
    }

    /**
     * dans notre entity nous avons une variable qui n'est pas en base que nous remplissons avec le chemin de l'image sur le serveur
     * comme nous utilisons une entity bien a part nous apellons la fonction getImage pour récupérer notre entité image
     * et récupérer le fichié qui lui est mappé avec VICH
     * on set le context pour éviter de se répéter
     * on ternourne le résultat sérialiser
     *
     * c'est encore obsucure mais ça marche
     */
    public function normalize($object, string $format = null, array $context = []) {
        $object->setImagePath($this->storage->resolveUri($object->getImage(), 'imageFile'));
        $context[self::ALREADY_USE] = true;
        return $this->normalizer->normalize($object, $format, $context);
    }
}