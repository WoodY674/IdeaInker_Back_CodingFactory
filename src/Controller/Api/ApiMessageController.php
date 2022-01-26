<?php

namespace App\Controller\Api;

use App\Repository\MessageRepository;
use App\Service\ApiService\ApiConstructorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class ApiMessageController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private MessageRepository $messageRepository;
    private ApiConstructorService $apiService;

    public function __construct(
        MessageRepository      $messageRepository,
        EntityManagerInterface $entityManager,
        ApiConstructorService  $apiService)
    {
        $this->messageRepository = $messageRepository;
        $this->entityManager = $entityManager;
        $this->apiService = $apiService;
    }

    #[Route('/message/{id}', methods: ['GET'])]
    public function getOneMessage($id):Response {
        $message = $this->messageRepository->findOneBy(['id' => $id, 'deletedAt' => null]);
        return $this->apiService->getResponseForApi($message);
    }

    #[Route('/message/{id}', methods: ['GET'])]
    public function getMessageByTchat($id):Response {
        $message = $this->messageRepository->findOneBy(['id' => $id, 'deletedAt' => null]);
        return $this->apiService->getResponseForApi($message);
    }

    #[Route('/message', methods: ['Message'])]
    public function createMessage():Response {
        return $this->json('message message');
    }


    #[Route('/message', methods: ['PATCH'])]
    public function updateMessage():Response {
        return $this->json('message message');
    }

    #[Route('/message', methods: ['DELETE'])]
    public function deleteMessage():Response {
        return $this->json('message message');
    }
}
