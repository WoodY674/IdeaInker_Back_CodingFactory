<?php

namespace App\Controller\Api;

use App\Repository\SalonRepository;
use App\Service\ApiService\ApiConstructorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class ApiSalonController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private SalonRepository $salonRepository;
    private ApiConstructorService $apiService;

    public function __construct(
        SalonRepository      $salonRepository,
        EntityManagerInterface $entityManager,
        ApiConstructorService  $apiService)
    {
        $this->salonRepository = $salonRepository;
        $this->entityManager = $entityManager;
        $this->apiService = $apiService;
    }

    #[Route('/salon', methods: ['GET'])]
    public function getAllSalon():Response {
        $salons = $this->salonRepository->findBy(['deletedAt' => null]);
        return $this->apiService->getResponseForApi($salons);
    }

    #[Route('/salon/{id}', methods: ['GET'])]
    public function getOneSalon($id):Response {
        $salon = $this->salonRepository->findOneBy(['id' => $id, 'deletedAt' => null]);
        return $this->apiService->getResponseForApi($salon);
    }

    #[Route('/salon', methods: ['POST'])]
    public function createSalon():Response {
        return $this->json('salon salon');
    }

    #[Route('/salon', methods: ['PUT'])]
    public function replaceSalon():Response {
        return $this->json('salon salon');
    }

    #[Route('/salon', methods: ['PATCH'])]
    public function updateSalon():Response {
        return $this->json('salon salon');
    }

    #[Route('/salon', methods: ['DELETE'])]
    public function deleteSalon():Response {
        return $this->json('salon salon');
    }
}
