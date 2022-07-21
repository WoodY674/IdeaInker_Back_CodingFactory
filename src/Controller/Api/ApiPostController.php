<?php

namespace App\Controller\Api;

use App\Entity\Image;
use App\Entity\Post;
use App\Entity\Salon;
use App\Entity\User;
use App\Repository\PostRepository;
use App\Repository\SalonRepository;
use App\Repository\UserRepository;
use App\Service\ApiService\ApiConstructorService;
use App\Service\fetchInformationEntity;
use App\Service\ImageService\ImageCreatorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api/post')]
class ApiPostController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private PostRepository $postRepository;
    private UserRepository $userRepository;
    private ApiConstructorService $apiService;
    private ImageCreatorService $imageCreatorService;
    private fetchInformationEntity $fetchInformationEntity;
    private SalonRepository $salonRepository;

    public function __construct(
        PostRepository $postRepository,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        ApiConstructorService $apiService,
        ImageCreatorService $imageCreatorService,fetchInformationEntity $fetchInformationEntity, SalonRepository $salonRepository
    ) {
        $this->postRepository = $postRepository;
        $this->entityManager = $entityManager;
        $this->apiService = $apiService;
        $this->userRepository = $userRepository;
        $this->imageCreatorService = $imageCreatorService;
        $this->fetchInformationEntity = $fetchInformationEntity;
        $this->salonRepository = $salonRepository;
    }

    #[Route('/', name: 'post_all', methods: ['GET'])]
    public function getAllPost(Request $request): Response
    {
        $posts = $this->postRepository->findBy(['deletedAt' => null], ['createdAt' => 'ASC']);

        $data = [];
        foreach ($posts as $key => $post) {
            $data[$key] = $this->getInformationForPost($post);
        }

        return $this->apiService->getResponseForApi($data);
    }

    #[Route('/{id}', name: 'post_show', methods: ['GET'])]
    public function getOnePost($id): Response
    {
        $post = $this->postRepository->findOneBy(['id' => $id, 'deletedAt' => null]);

        $data = $this->getInformationForPost($post);

        return $this->apiService->getResponseForApi($data);
    }

    #[Route('/', name: 'post_new', methods: ['POST'])]
    public function newPost(Request $request): Response {
        $json = $this->apiService->getJsonBodyFromRequest($request);
        if(!isset($json[Post::IMAGE])) {
            return $this->json('Donnée manquante', 405);
        }
        if(key_exists(POST::CREATED_BY, $json)) {
            $user = $this->userRepository->findOneBy(['id' => $json[Post::CREATED_BY]]);
            if(isset($user)) {
                $post = $this->apiService->getJsonBodyFromRequest($request, Post::class);
                $post->setCreatedBy($user);
                $this->entityManager->persist($post);
            } else {
                return $this->json('User non trouvé', 405);
            }
        } elseif (key_exists(POST::CREATED_BY_SALON, $json)) {
            $salon = $this->salonRepository->findOneBy(['id' => $json[Post::CREATED_BY_SALON]]);
            if(isset($salon)) {
                $post = $this->apiService->getJsonBodyFromRequest($request, Post::class);
                $post->setSalon($sa
            } else {
                return $this->json('salon non trouvé', 405);
            }
        }
        $this->entityManager->flush();
        return $this->json('', 201);
    }

    #[Route('/{id}', name: 'post_replace', methods: ['PUT'])]
    public function replacePost($id, Request $request): Response {
        $post = $this->postRepository->findOneBy(['id' => $id]);
        if(!isset($post)) {
            return $this->json('post not found', 403);
        }

        $json = $this->apiService->getJsonBodyFromRequest($request);
        $allMethods = $this->apiService->getSetFunctionFromJsonKey(array_keys($json), $post::class);

        foreach ($allMethods as $key => $method) {
            if($key === Post::IMAGE) {
                try {
                    $newImage = $this->imageCreatorService->convertImages64ToEntity($json[$key]);
                    $this->entityManager->persist($newImage);
                    $post->$method($json[$key]);
                } catch (\Exception $e) {
                    continue;
                }
            }
            $post->$method($json[$key]);
        }
        $this->entityManager->flush();
        return $this->apiService->getResponseForApi($post);
    }

    #[Route('/{id}', name: 'post_update', methods: ['PATCH'])]
    public function updatePost(): Response
    {
        return $this->json('post post');
    }

    #[Route('/{id}', name: 'post_delete', methods: ['DELETE'])]
    public function deletePost($id): Response
    {
        try {
            $post = $this->postRepository->findOneBy(['id' => $id]);
            if (!$post) {
                throw new \Exception();
            }
            $post->setDeletedAt(new \DateTimeImmutable());
            $this->entityManager->flush();

            return $this->apiService->getResponseForApi('Post deleted successfully');
        } catch (\Exception $exception) {
            return $this->apiService->getResponseForApi('Post not found')->setStatusCode(404);
        }
    }

    public function getInformationForPost($post) {
        return $this->fetchInformationEntity->getInformationForPost($post);
    }
}
