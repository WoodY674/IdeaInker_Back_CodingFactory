<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Meeting;
use App\Entity\Post;
use App\Entity\Salon;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;

/**
 * Extend de abstactController pour avoir l'autowire (besoin de changer car l'autowire doit se passer dans le yaml.
 */
class ImageController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * quand notre classe se fait invoke par une entity (voir l'entité post) nous arrivons ici
     * nous récupérons les données (entité/files)
     * nous les ajoutons dans un tableau pour les transportez de fonction en fonction plus simplement.
     *
     * il faut retournée l'entité travailé
     */
    public function __invoke(Request $request)
    {
        $entity = $request->attributes->get('data');
        $files = $request->files->get('files');
        $dataTools = [
            'entity' => $entity,
            'request' => $request,
            'files' => $files,
        ];

        return $this->executeIfEntityValid($dataTools);
    }

    /**
     * @return Post
     *
     * nous vérifions les instances et effectuons un traitement spécifique pour chaque entité
     * puis on retourne lentité traiter
     */
    private function executeIfEntityValid(array $dataTools)
    {
        $entity = $dataTools['entity'];
        switch ($entity) {
            case $entity instanceof Meeting:
            case $entity instanceof Salon:
            case $entity instanceof Post:
                return $this->setImageForPost($dataTools['entity'], $dataTools['files']);
            case $entity instanceof User:
            default:
                throw new RuntimeException('Entity inconnue');
        }
    }

    /**
     * @return Post
     *
     * on crée une image (car une seul peut etre fait) on la persite
     * et set le post (sans le persit cette foit si car ça sera fait par apiplatform
     *
     * et on return
     */
    private function setImageForPost(Post $post, File $file)
    {
        $image = new Image();
        $image->setImageFile($file);

        $this->entityManager->persist($image);

        $post->setImage($image);

        return $post;
    }
}
