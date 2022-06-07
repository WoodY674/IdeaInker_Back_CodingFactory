<?php

namespace App\Controller\Api;

use App\Entity\Salon;
use App\Repository\SalonRepository;
use App\Repository\UserRepository;
use App\Service\ApiService\ApiConstructorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api2/salon')]
class ApiSalonController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private SalonRepository $salonRepository;
    private UserRepository $userRepository;
    private ApiConstructorService $apiService;

    public function __construct(
        SalonRepository $salonRepository,
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        ApiConstructorService $apiService)
    {
        $this->salonRepository = $salonRepository;
        $this->entityManager = $entityManager;
        $this->apiService = $apiService;
        $this->userRepository = $userRepository;
    }

    #[Route('/', name: 'salon_all', methods: ['GET'])]
    public function getAllSalon(): Response
    {
        $salons = $this->salonRepository->findBy(['deletedAt' => null]);

        return $this->apiService->getResponseForApi($salons);
    }

    #[Route('/{id}', name: 'salon_show', methods: ['GET'])]
    public function getOneSalon($id): Response
    {
        $salon = $this->salonRepository->findOneBy(['id' => $id, 'deletedAt' => null]);

        return $this->apiService->getResponseForApi($salon);
    }

    #[Route('/', name: 'salon_new', methods: ['POST'])]
    public function newSalon(Request $request): Response
    {
        try {
            $salon = $this->apiService->getJsonBodyFromRequest($request, Salon::class);
            $this->entityManager->persist($salon);
            $this->entityManager->flush();

            return $this->json($salon);
        } catch (\Exception $exception) {
            return $this->apiService->getResponseForApi('Data no valid or user not found')->setStatusCode(422);
        }
    }

    #[Route('/{id}', name: 'salon_replace', methods: ['PUT'])]
    public function replaceSalon(): Response
    {
        return $this->json('salon salon');
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
}
