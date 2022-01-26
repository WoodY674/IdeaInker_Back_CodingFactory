<?php

namespace App\Controller\Api;

use App\Entity\Post;
use App\Repository\MessageRepository;
use App\Service\ApiService\ApiConstructorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class ApiPostController extends AbstractController {

    private EntityManagerInterface $entityManager;
    private MessageRepository $postRepository;
    private ApiConstructorService $apiService;

    public function __construct(
        MessageRepository      $postRepository,
        EntityManagerInterface $entityManager,
        ApiConstructorService  $apiService)
    {
        $this->postRepository = $postRepository;
        $this->entityManager = $entityManager;
        $this->apiService = $apiService;
    }

    #[Route('/post', methods: ['GET'])]
    public function getAllPost(Response $response):Response {
        $posts = $this->postRepository->findBy(['deletedAt' => null]);
        return $this->apiService->getResponseForApi($posts);
    }

    #[Route('/post/{id}', methods: ['GET'])]
    public function getOnePost($id):Response {
        $post = $this->postRepository->findOneBy(['id' => $id, 'deletedAt' => null]);
        return $this->apiService->getResponseForApi($post);
    }

    #[Route('/post', methods: ['POST'])]
    public function createPost(Response $response):Response {
        try {

            /*if (!$request || !$request->get('username') || !$request->request->get('description')){
                throw new \Exception();
            }*/
            $post = new Post();
            //$post->setContent();
            //$post->setImage();
            return $this->json($response->getContent());
        } catch (\Exception $exception) {
            return $this->apiService->getResponseForApi("Data no valid")->setStatusCode(422);
        }
    }

    #[Route('/post', methods: ['PUT'])]
    public function replacePost():Response {
        return $this->json('post post');
    }

    #[Route('/post', methods: ['PATCH'])]
    public function updatePost():Response {
        return $this->json('post post');
    }

    #[Route('/post', methods: ['DELETE'])]
    public function deletePost():Response {
        return $this->json('post post');
    }
}
