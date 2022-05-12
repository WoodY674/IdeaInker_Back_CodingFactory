<?php

namespace App\Controller\Api;

use App\Entity\Image;
use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use App\Service\ApiService\ApiConstructorService;
use App\Service\ImageService\ImageCreatorService;
use Doctrine\ORM\EntityManagerInterface;
use Metadata\MetadataFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api2/post')]
class ApiPostController extends AbstractController
{

    private EntityManagerInterface $entityManager;
    private PostRepository $postRepository;
    private UserRepository $userRepository;
    private ApiConstructorService $apiService;
    private ValidatorInterface $validator;

    public function __construct(
        PostRepository         $postRepository,
        UserRepository         $userRepository,
        EntityManagerInterface $entityManager,
        ApiConstructorService  $apiService,
        ValidatorInterface $validator)
    {
        $this->postRepository = $postRepository;
        $this->entityManager = $entityManager;
        $this->apiService = $apiService;
        $this->userRepository = $userRepository;
        $this->validator = $validator;
    }

    #[Route('/', name: 'post_all', methods: ['GET'])]
    public function getAllPost(Request $request): Response {
        $posts = $this->postRepository->findBy(['deletedAt' => null]);
        return $this->apiService->getResponseForApi($posts);
    }

    #[Route('/{id}', name: 'post_show', methods: ['GET'])]
    public function getOnePost($id, Request $request): Response {
        $post = $this->postRepository->findOneBy(['id' => $id, 'deletedAt' => null]);

        return $this->apiService->getResponseForApi($post);
    }
    
    #[Route('/', name: 'post_new', methods: ['POST'])]
    public function newPost(Request $request): Response {
        $post = $this->apiService->getJsonBodyFromRequest($request, Post::class);
        $this->entityManager->persist($post);
        $this->entityManager->flush();
        return $this->json($post, 201);
    }

    #[Route('/{id}', name: 'post_replace', methods: ['PUT'])]
    public function replacePost(): Response
    {
        return $this->json('post post',201);
    }

    #[Route('/{id}', name: 'post_update', methods: ['PATCH'])]
    public function updatePost(): Response
    {
        return $this->json('post post');
    }

    #[Route('/{id}', name: 'post_delete', methods: ['DELETE'])]
    public function deletePost($id): Response{
        try {
            $post = $this->postRepository->findOneBy(['id' => $id]);
            if (!$post) {
                throw new \Exception();
            }
            $post->setDeletedAt(new \DateTimeImmutable());
            $this->entityManager->flush();

            return $this->apiService->getResponseForApi("Post deleted successfully");
        }catch (\Exception $exception) {
            return $this->apiService->getResponseForApi("Post not found")->setStatusCode(404);
        }
    }
}
