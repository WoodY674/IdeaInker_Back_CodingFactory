<?php

namespace App\Controller\Api;

use App\Entity\Image;
use App\Entity\Salon;
use App\Entity\User;
use App\Repository\SalonRepository;
use App\Repository\UserRepository;
use App\Service\ApiService\ApiConstructorService;
use App\Service\ImageService\ImageCreatorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/salon')]
class ApiSalonController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private SalonRepository $salonRepository;
    private UserRepository $userRepository;
    private ApiConstructorService $apiService;
    private ImageCreatorService $imageCreatorService;

    public function __construct(
        SalonRepository $salonRepository,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        ApiConstructorService $apiService,
        ImageCreatorService $imageCreatorService)
    {
        $this->salonRepository = $salonRepository;
        $this->entityManager = $entityManager;
        $this->apiService = $apiService;
        $this->userRepository = $userRepository;
        $this->imageCreatorService = $imageCreatorService;
    }

    #[Route('/', name: 'salon_all', methods: ['GET'])]
    public function getAllSalon(): Response
    {
        $salons = $this->salonRepository->findBy(['deletedAt' => null], ['createdAt' => 'ASC']);

        $data = [];
        foreach ($salons as $key => $salon) {
            $data[$key] = $this->getInformationFromSalon($salon);
        }

        return $this->apiService->getResponseForApi($data);
    }

    #[Route('/{id}', name: 'salon_show', methods: ['GET'])]
    public function getOneSalon($id): Response
    {
        $salon = $this->salonRepository->findOneBy(['id' => $id, 'deletedAt' => null]);

        $data = $this->getInformationFromSalon($salon);

        return $this->apiService->getResponseForApi($data);
    }

    #[Route('/', name: 'salon_new', methods: ['POST'])]
    public function newSalon(Request $request): Response
    {
        $json = $this->apiService->getJsonBodyFromRequest($request);
        if(!isset($json[Salon::MANAGER]) && isset($json[Salon::SALON_IMAGE])) {
            return $this->json('Donnée manquante', 405);
        }
        $user = $this->userRepository->findOneBy(['id' => $json[Salon::MANAGER]]);
        if(isset($user)) {
            $salon = $this->apiService->getJsonBodyFromRequest($request, Salon::class);
            $salon->setManager($user);
            $this->entityManager->persist($salon);
            $this->entityManager->flush();
            return $this->apiService->getResponseForApi($salon);
        } else {
            return $this->json('User non trouvé', 405);
        }
    }

    #[Route('/{id}', name: 'salon_replace', methods: ['PUT'])]
    public function replaceSalon(Request $request, $id): Response
    {
        $salon = $this->salonRepository->findOneBy(['id' => $id]);
        if(!isset($salon)) {
            return $this->json('post not found', 403);
        }

        $json = $this->apiService->getJsonBodyFromRequest($request);
        $allMethods = $this->apiService->getSetFunctionFromJsonKey(array_keys($json), $salon::class);

        foreach ($allMethods as $key => $method) {
            if($key === Salon::SALON_IMAGE) {
                if(gettype($json[$key]) === "integer") {
                    continue;
                } else {
                    $newImage = $this->imageCreatorService->convertImages64ToEntity($json[$key]);
                    $this->entityManager->persist($newImage);
                    $salon->$method($json[$key]);
                }
            }
            $salon->$method($json[$key]);
        }
        $this->entityManager->flush();
        return $this->apiService->getResponseForApi($salon);
    }

    #[Route('/{id}', name: 'salon_update', methods: ['PATCH'])]
    public function updateSalon(): Response
    {
        return $this->json('salon salon');
    }

    #[Route('/{id}', name: 'salon_delete', methods: ['DELETE'])]
    public function deleteSalon($id): Response
    {
        try {
            $salon = $this->salonRepository->findOneBy(['id' => $id]);
            if (!$salon) {
                throw new \Exception();
            }

            $salon->setDeletedAt(new \DateTimeImmutable());
            $this->entityManager->flush();

            return $this->apiService->getResponseForApi('Salon deleted successfully');
        } catch (\Exception $exception) {
            return $this->apiService->getResponseForApi('Salon not found')->setStatusCode(404);
        }
    }

    private function getInformationFromSalon($salon){
        $metadataSalon = $this->getDoctrine()->getManager()->getMetadataFactory()->getMetadataFor(Salon::class);

        $data = $this->apiService->getSimpleDataFromEntity($salon, $metadataSalon);

        $imageSalon = $salon->getSalonImage();
        if(isset($imageSalon)) {
            $metadataImage = $this->getDoctrine()->getManager()->getMetadataFactory()->getMetadataFor(Image::class);
            $data[Salon::SALON_IMAGE] = $this->apiService->getSimpleDataFromEntity($imageSalon, $metadataImage);
        } else {
            $data[Salon::SALON_IMAGE] = null;
        }

        $manager = $salon->getManager();
        if(isset($manager)) {
            $metadataImage = $this->getDoctrine()->getManager()->getMetadataFactory()->getMetadataFor(User::class);
            $data[Salon::MANAGER] = $this->apiService->getSimpleDataFromEntity($manager, $metadataImage);
            unset($data[Salon::MANAGER]['password']);
            unset($data[Salon::MANAGER]['roles']);
            $imageManager = $manager->getProfileImage();
            if(isset($imageManager)) {
                $metadataImage = $this->getDoctrine()->getManager()->getMetadataFactory()->getMetadataFor(Image::class);
                $data[Salon::MANAGER][User::PROFILE_IMAGE] = $this->apiService->getSimpleDataFromEntity($imageManager, $metadataImage);
            } else {
                $data[Salon::MANAGER][User::PROFILE_IMAGE] = null;
            }

        } else {
            $data[Salon::MANAGER] = null;
        }

        return $data;
    }
}
