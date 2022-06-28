<?php

namespace App\Controller\Api;

use App\Entity\Image;
use App\Entity\Post;
use App\Entity\User;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Service\ApiService\ApiConstructorService;
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

    public function __construct(
        PostRepository $postRepository,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        ApiConstructorService $apiService,
    ) {
        $this->postRepository = $postRepository;
        $this->entityManager = $entityManager;
        $this->apiService = $apiService;
        $this->userRepository = $userRepository;
    }

    #[Route('/', name: 'post_all', methods: ['GET'])]
    public function getAllPost(Request $request): Response
    {
        $posts = $this->postRepository->findBy(['deletedAt' => null], ['createdAt' => 'ASC']);

        $metadataSalon = $this->getDoctrine()->getManager()->getMetadataFactory()->getMetadataFor(Post::class);
        $data = [];
        foreach ($posts as $key => $post) {
            $data[$key] = $this->apiService->getSimpleDataFromEntity($post, $metadataSalon);
            $imagePost = $post->getImage();
            if(isset($imagePost)) {
                $metadataImage = $this->getDoctrine()->getManager()->getMetadataFactory()->getMetadataFor(Image::class);
                $data[$key][Post::IMAGE] = $this->apiService->getSimpleDataFromEntity($imagePost, $metadataImage);
            } else {
                $data[$key][Post::IMAGE] = null;
            }
            $createdBy = $post->getCreatedBy();
            if(isset($createdBy)) {
                $metadataUser = $this->getDoctrine()->getManager()->getMetadataFactory()->getMetadataFor(User::class);
                $data[$key][Post::CREATED_BY] = $this->apiService->getSimpleDataFromEntity($createdBy, $metadataUser);
                unset($data[$key][Post::CREATED_BY]['password']);
                unset($data[$key][Post::CREATED_BY]['roles']);
                $imageCreator = $createdBy->getProfileImage();
                if(isset($imageCreator)) {
                    $metadataImage = $this->getDoctrine()->getManager()->getMetadataFactory()->getMetadataFor(Image::class);
                    $data[$key][Post::CREATED_BY][User::PROFILE_IMAGE] = $this->apiService->getSimpleDataFromEntity($imageCreator, $metadataImage);
                } else {
                    $data[$key][Post::CREATED_BY][User::PROFILE_IMAGE] = null;
                }

            } else {
                $data[$key][Post::CREATED_BY] = null;
            }
        }

        return $this->apiService->getResponseForApi($data);
    }

    #[Route('/{id}', name: 'post_show', methods: ['GET'])]
    public function getOnePost($id, Request $request): Response
    {
        $post = $this->postRepository->findOneBy(['id' => $id, 'deletedAt' => null]);

        $metadataSalon = $this->getDoctrine()->getManager()->getMetadataFactory()->getMetadataFor(Post::class);
        $data = $this->apiService->getSimpleDataFromEntity($post, $metadataSalon);

        $imagePost = $post->getImage();
        if(isset($imagePost)) {
            $metadataImage = $this->getDoctrine()->getManager()->getMetadataFactory()->getMetadataFor(Image::class);
            $data[Post::IMAGE] = $this->apiService->getSimpleDataFromEntity($imagePost, $metadataImage);
        } else {
            $data[Post::IMAGE] = null;
        }
        $createdBy = $post->getCreatedBy();
        if(isset($createdBy)) {
            $metadataUser = $this->getDoctrine()->getManager()->getMetadataFactory()->getMetadataFor(User::class);
            $data[Post::CREATED_BY] = $this->apiService->getSimpleDataFromEntity($createdBy, $metadataUser);
            unset($data[Post::CREATED_BY]['password']);
            unset($data[Post::CREATED_BY]['roles']);
            $imageCreator = $createdBy->getProfileImage();
            if(isset($imageCreator)) {
                $metadataImage = $this->getDoctrine()->getManager()->getMetadataFactory()->getMetadataFor(Image::class);
                $data[Post::CREATED_BY][User::PROFILE_IMAGE] = $this->apiService->getSimpleDataFromEntity($imageCreator, $metadataImage);
            } else {
                $data[Post::CREATED_BY][User::PROFILE_IMAGE] = null;
            }

        } else {
            $data[Post::CREATED_BY] = null;
        }

        return $this->apiService->getResponseForApi($data);
    }

    #[Route('/', name: 'post_new', methods: ['POST'])]
    public function newPost(Request $request): Response
    {
        $post = $this->apiService->getJsonBodyFromRequest($request, Post::class);
        $this->entityManager->persist($post);
        $this->entityManager->flush();

        return $this->json($post, 201);
    }

    #[Route('/{id}', name: 'post_replace', methods: ['PUT'])]
    public function replacePost(): Response
    {
        return $this->json('post post', 201);
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
}
