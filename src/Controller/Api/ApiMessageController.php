<?php

namespace App\Controller\Api;

use App\Entity\Image;
use App\Entity\Message;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use App\Service\ApiService\ApiConstructorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api2/message')]
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
        $messages = $this->messageRepository->findBy(['deletedAt' => null], ['sendAt' => 'DESC']);

        return $this->apiService->getResponseForApi($messages);
    }

    #[Route('/{id}', name: 'message_show', methods: ['GET'])]
    public function getOneMessage($id): Response
    {
        $message = $this->messageRepository->findOneBy(['id' => $id, 'deletedAt' => null]);

        return $this->apiService->getResponseForApi($message);
    }

    #[Route('/sendBy', name: 'message_show_sender', methods: ['GET'])]
    public function getMessageBySender($sendBy): Response
    {
        $message = $this->messageRepository->findBy(['sendBy' => '1', 'deletedAt' => null]);

        return $this->apiService->getResponseForApi($message);
    }

    #[Route('/', name: 'message_new', methods: ['POST'])]
    public function newMessage(): Response
    {
        try {
            $response = $this->apiService->getJsonBodyFromRequest();
            if (!empty($request)) {
                throw new \Exception();
            }
            $user = $this->userRepository->findOneBy(['id' => $response['manager'], 'deletedAt' => null]);
            if (!$user) {
                throw new \Exception();
            }

            $image = new Image();
            $image->setImage($response['image']);
            $this->entityManager->persist($image);

            $message = new Message();

            $this->entityManager->persist($message);
            $this->entityManager->flush();

            return $this->apiService->getResponseForApi($user);
        } catch (\Exception $exception) {
            return $this->apiService->getResponseForApi('Data no valid or user not found')->setStatusCode(422);
        }
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
