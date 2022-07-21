<?php

namespace App\Controller\Api;

use App\Entity\Notice;
use App\Entity\Salon;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\ApiService\ApiConstructorService;
use App\Service\ImageService\ImageCreatorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


#[Route('/api/users')]
class ApiUserController extends AbstractController
{
    private ApiConstructorService $apiConstructorService;
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;
    private ImageCreatorService $imageCreatorService;

    public function __construct(ApiConstructorService $apiConstructorService, UserRepository $userRepository, EntityManagerInterface $entityManager, ImageCreatorService $imageCreatorService) {
        $this->apiConstructorService = $apiConstructorService;
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->imageCreatorService = $imageCreatorService;
    }

    #[Route('/me', name: 'me', methods: ['GET'])]
    public function userMe(): Response {
        if($this->getUser() === null) {
            return $this->json("not connected", 405);
        }

        $user = $this->getUser();
        $data = $this->getInformationFromUser($user);

        return $this->apiConstructorService->getResponseForApi($data);
    }
    #[Route('/register', name: 'register', methods: ['POST'])]
    public function registerUser(Request $request, UserPasswordHasherInterface $passwordHasher) {
        $user = $this->apiConstructorService->getJsonBodyFromRequest($request, User::class);
        $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
        $user->setPassword($hashedPassword);
        try {
            $this->entityManager->persist($user);
            $this->entityManager->flush();
        } catch (\Exception $exception){
            $this->json("information manquante ou pseudo déjà utilisé ou email déjà utlisé", 403);
        }
        return $this->json("", 201);
    }

    #[Route('/{id}', name: 'modify', methods: ['PATCH'])]
    public function modifyuser($id, Request $request) {
        $user = $this->userRepository->findOneBy(['id' => $id]);
        if (!$user) {
            return $this->json('error user not found', '405');
        }

        $json = $this->apiConstructorService->getJsonBodyFromRequest($request);
        $allMethods = $this->apiConstructorService->getSetFunctionFromJsonKey(array_keys($json), $user::class);
        foreach ($allMethods as $key => $method) {
            if($key === User::PROFILE_IMAGE) {
                try {
                    $newImage = $this->imageCreatorService->convertImages64ToEntity($json[$key]);
                    $this->entityManager->persist($newImage);
                    $user->$method($newImage);
                } catch (\Exception $e) {
                    continue;
                }
            } else {
                $user->$method($json[$key]);
            }
        }
        $this->entityManager->flush();
        return $this->json("tkt c'est bon");
    }

    #[Route('/{id}', name: 'getOneUser', methods: ['GET'])]
    public function getOneUser($id) {
        $user = $this->userRepository->findOneBy(['id' => $id]);

        if($user === null) {
            return $this->json("not user Found", "403");
        }
        $this->getInformationFromUser($user);
        $user = $this->getInformationFromUser($user);
        return $this->apiConstructorService->getResponseForApi($user);
    }

    private function getInformationFromUser($user) {

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

        $workinSalons = $user->getWorkingSalon();
        if(isset($workinSalons)) {
            $metadataSalon = $this->getDoctrine()->getManager()->getMetadataFactory()->getMetadataFor(Salon::class);

            foreach ($workinSalons as $key => $salon) {
                $data[User::WORKING_SALON] = $this->apiConstructorService->getSimpleDataFromEntity($workinSalons, $metadataSalon);
                $image = $salon->getSalonImage();

                if(isset($imageSalon)) {
                    $metadataImage = $this->getDoctrine()->getManager()->getMetadataFactory()->getMetadataFor($image::class);
                    $data[User::WORKING_SALON][$key][Salon::SALON_IMAGE] = $this->apiConstructorService->getSimpleDataFromEntity($image, $metadataImage);
                } else {
                    $data[User::WORKING_SALON][$key][Salon::SALON_IMAGE] = null;
                }
            }
        } else {
            $data[User::WORKING_SALON] = null;
        }

        $notices = $user->getNotices();
        if (isset($notices) && count($notices) > 0) {
            $starsAll = 0;
            foreach ($notices as $notice) {
                $data[User::NOTICES]['all_notices'][] = [
                    Notice::ID => $notice->getId(),
                    Notice::STARS => $notice->getStars(),
                    Notice::COMMENT => $notice->getComment(),
                ];
                $starsAll += $notice->getStars();
            }
            $result = $starsAll / count($notices);
            $data[Salon::NOTICES]['average'] = number_format((float)$result, 1, '.', '');
        }

        return $data;
    }
}
