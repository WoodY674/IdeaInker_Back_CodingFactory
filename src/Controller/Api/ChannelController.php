<?php

namespace App\Controller\Api;

use App\Entity\Channel;
use App\Repository\ChannelRepository;
use App\Repository\UserRepository;
use App\Service\ApiService\ApiConstructorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/channel')]
class ChannelController extends AbstractController {
    private ChannelRepository $channelRepository;
    private ApiConstructorService $apiService;
    private UserRepository $userRepository;

    public function __construct(ChannelRepository $channelRepository, ApiConstructorService $apiService, UserRepository $userRepository) {
        $this->channelRepository = $channelRepository;
        $this->apiService = $apiService;
        $this->userRepository = $userRepository;
    }

    #[Route('/{userId}', name: 'channel', methods: ['GET'])]
    public function getAllSalon($userId): Response {
        $channels = $this->channelRepository->findAll();
        $user = $this->userRepository->findOneBy(['id' => $userId]);
        $data = [];
        foreach ($channels as $channel) {
            $information = $this->getChannelInformation($channel);
            if(in_array($user->getPseudo(), $information[Channel::USER_INSIDE]) ){
                $data[] = $information;
            }
        }
        return $this->apiService->getResponseForApi($data);
    }

    private function getChannelInformation($channel) {
        $metadataChannel = $this->getDoctrine()->getManager()->getMetadataFactory()->getMetadataFor(Channel::class);

        $data = $this->apiService->getSimpleDataFromEntity($channel, $metadataChannel);

        $messages = $channel->getMessages();
        $users = $channel->getUsersInside();
        foreach ($users as $user) {
            $data[Channel::USER_INSIDE][] = $user->getPseudo();
        }

        $message = $messages->last();
        if($message) {
            $data[Channel::LAST_MESSAGE] = $message->getMessage();
        }

        return $data;
    }
}
