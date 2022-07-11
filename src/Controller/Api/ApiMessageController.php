<?php

namespace App\Controller\Api;

use App\Entity\Image;
use App\Entity\Message;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use App\Service\ApiService\ApiConstructorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/message')]
class ApiMessageController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private MessageRepository $messageRepository;
    private UserRepository $userRepository;
    private ApiConstructorService $apiService;

    public function __construct(
        MessageRepository $messageRepository,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        ApiConstructorService $apiService)
    {
        $this->messageRepository = $messageRepository;
        $this->entityManager = $entityManager;
        $this->apiService = $apiService;
        $this->userRepository = $userRepository;
    }

    #[Route('/', name: 'message_all', methods: ['GET'])]
    public function getAllMessage(): Response
    {

        return $this->apiService->getResponseForApi(null);
    }

    #[Route('/{id}', name: 'message_show', methods: ['GET'])]
    public function getOneMessage($id): Response
    {
        $message = $this->messageRepository->findOneBy(['id' => $id]);

        return $this->apiService->getResponseForApi($message);
    }

    #[Route('/sendBy', name: 'message_show_sender', methods: ['GET'])]
    public function getMessageBySender($sendBy): Response
    {
        $message = $this->messageRepository->findBy(['sendBy' => '1', 'deletedAt' => null]);

        return $this->apiService->getResponseForApi($message);
    }

    #[Route('/', name: 'message_new', methods: ['POST'])]
    public function newMessage(Request $request): Response
    {
        $message = $this->apiService->getJsonBodyFromRequest($request, Message::class);
        $this->entityManager->persist($message);
        $this->entityManager->flush();

        return $this->apiService->getResponseForApi($message);
    }

    #[Route('/{id}', name: 'message_replace', methods: ['PUT'])]
    public function replaceMessage(): Response
    {
        return $this->json('message message');
    }

    #[Route('/{id}', name: 'message_update', methods: ['PATCH'])]
    public function updateMessage(): Response
    {
        return $this->json('message message');
    }

    #[Route('/{id}', name: 'message_delete', methods: ['DELETE'])]
    public function deleteMessage($id): Response
    {
        try {
            $message = $this->messageRepository->findOneBy(['id' => $id]);
            if (!$message) {
                throw new \Exception();
            }

            $this->entityManager->remove($message);
            $this->entityManager->flush();

            return $this->apiService->getResponseForApi('Message deleted successfully');
        } catch (\Exception $exception) {
            return $this->apiService->getResponseForApi('Message not found')->setStatusCode(404);
        }
    }
}
