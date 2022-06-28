<?php

namespace App\Controller\Api;

use App\Entity\Salon;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\ApiService\ApiConstructorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/users')]
class ApiUserController extends AbstractController
{
    private ApiConstructorService $apiConstructorService;
    private UserRepository $userRepository;

    public function __construct(ApiConstructorService $apiConstructorService, UserRepository $userRepository) {
        $this->apiConstructorService = $apiConstructorService;
        $this->userRepository = $userRepository;
    }

    #[Route('/me', name: 'me', methods: ['GET'])]
    public function userMe(): Response {
        if($this->getUser() === null) {
            return $this->json("not connected", "403");
        }

        $user = $this->getUser();

        $metadataUser = $this->getDoctrine()->getManager()->getMetadataFactory()->getMetadataFor($user::class);
        $data = $this->apiConstructorService->getSimpleDataFromEntity($user, $metadataUser);

        $image = $user->getProfileImage();
        if(isset($image)) {
            $metadataImage = $this->getDoctrine()->getManager()->getMetadataFactory()->getMetadataFor($image::class);
            $data[User::PROFILE_IMAGE] = $this->apiConstructorService->getSimpleDataFromEntity($image, $metadataImage);
        } else {
            $data[User::PROFILE_IMAGE] = null;
        }

        $salons = $user->getSalons();
        if(isset($salons)) {
            $metadataSalon = $this->getDoctrine()->getManager()->getMetadataFactory()->getMetadataFor(Salon::class);

            foreach ($salons as $key => $salon) {
                $data['salons'][$key] = $this->apiConstructorService->getSimpleDataFromEntity($salon, $metadataSalon);
                $image = $salon->getSalonImage();

                if(isset($imageSalon)) {
                    $metadataImage = $this->getDoctrine()->getManager()->getMetadataFactory()->getMetadataFor($image::class);
                    $data['salons'][$key][Salon::SALON_IMAGE] = $this->apiConstructorService->getSimpleDataFromEntity($image, $metadataImage);
                } else {
                    $data['salons'][$key][Salon::SALON_IMAGE] = null;
                }
            }
        } else {
            $data['salons'] = null;
        }

        return $this->apiConstructorService->getResponseForApi($data);
    }
    #[Route('/register', name: 'register', methods: ['POST'])]
    public function registerUser() {

    }
}
