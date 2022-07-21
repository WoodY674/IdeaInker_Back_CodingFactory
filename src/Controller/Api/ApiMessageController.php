<?php

namespace App\Controller\Api;

use App\Entity\Image;
use App\Entity\Message;
use App\Entity\User;
use App\Repository\ChannelRepository;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use App\Service\ApiService\ApiConstructorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/messages')]
class ApiMessageController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private MessageRepository $messageRepository;
    private UserRepository $userRepository;
    private ApiConstructorService $apiService;
    private ChannelRepository $channelRepository;

    public function __construct(
        MessageRepository $messageRepository,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        ApiConstructorService $apiService,
        ChannelRepository $channelRepository)
    {
        $this->messageRepository = $messageRepository;
        $this->entityManager = $entityManager;
        $this->apiService = $apiService;
        $this->userRepository = $userRepository;
        $this->channelRepository = $channelRepository;
    }

    #[Route('/{id}', name: 'get_message_by_id_channel', methods: ['GET'])]
    public function getMessageByChannel($id): Response
    {
        $messages = $this->messageRepository->findBy(['channel' => $id], ['sendAt' => 'DESC']);
        $data = [];
        foreach ($messages as $key => $message) {
            $data[$key] = $this->getMessageInformation($message);
        }
        return $this->apiService->getResponseForApi($data);
    }

    #[Route('/{id}', name: 'new_message', methods: ['POST'])]
    public function newMesage($id, Request $request): Response
    {
        $channel = $this->channelRepository->findOneBy(['id' => $id]);
        if(!$channel) {
            return $this->json('channel non trouvé');
        }
        $message = $this->apiService->getJsonBodyFromRequest($request, Message::class);
        $message->setSendBy($this->getUser());
        $this->entityManager->persist($message);
        $this->entityManager->flush();
        return $this->json('OK', 201);
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

    public function getMessageInformation($message) {
        $metadataMessage = $this->getDoctrine()->getManager()->getMetadataFactory()->getMetadataFor(Message::class);
        $data = $this->apiService->getSimpleDataFromEntity($message, $metadataMessage);

        $sendBy = $message->getSendBy();
        if(isset($sendBy)) {
            $metadataImage = $this->getDoctrine()->getManager()->getMetadataFactory()->getMetadataFor(User::class);
            $data[Message::SEND_BY] = $this->apiService->getSimpleDataFromEntity($sendBy, $metadataImage);
            unset($data[Message::SEND_BY]['password']);
            unset($data[Message::SEND_BY]['roles']);
            $imageSendBy = $sendBy->getProfileImage();
            if(isset($imageSendBy)) {
                $metadataImage = $this->getDoctrine()->getManager()->getMetadataFactory()->getMetadataFor(Image::class);
                $data[Message::SEND_BY][User::PROFILE_IMAGE] = $this->apiService->getSimpleDataFromEntity($imageSendBy, $metadataImage);
            } else {
                $data[Message::SEND_BY][User::PROFILE_IMAGE] = null;
            }

        } else {
            $data[Message::SEND_BY] = null;
        }

        return $data;
    }
}
