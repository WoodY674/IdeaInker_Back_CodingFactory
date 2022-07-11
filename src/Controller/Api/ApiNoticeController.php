<?php

namespace App\Controller\Api;

use App\Entity\Notice;
use App\Repository\SalonRepository;
use App\Repository\UserRepository;
use App\Service\ApiService\ApiConstructorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/notice')]
class ApiNoticeController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;
    private ApiConstructorService $apiService;
    private SalonRepository $salonRepository;

    public function __construct(
        UserRepository $userRepository,
        EntityManagerInterface $entityManager,
        ApiConstructorService $apiService,
        SalonRepository $salonRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->apiService = $apiService;
        $this->userRepository = $userRepository;
        $this->salonRepository = $salonRepository;
    }
    #[Route('/', name: 'notice_new', methods: ['POST'])]
    public function newSalon(Request $request): Response
    {
        $json = $this->apiService->getJsonBodyFromRequest($request);
        if(!isset($json[Notice::USER_NOTING])) {
            return $this->json('Donnée manquante', 405);
        }

        $userNoting = $this->userRepository->findOneBy(['id' => $json[Notice::USER_NOTING]]);
        if(isset($userNoting)) {
            $notice = $this->apiService->getJsonBodyFromRequest($request, Notice::class);
            $notice->setUserNoting($userNoting);

            if (key_exists(Notice::USER_NOTED, $json) && isset($json[Notice::USER_NOTED])) {
                $userNoted = $this->userRepository->findOneBy(['id' => $json[Notice::USER_NOTED]]);
                if(!$userNoted) {
                    return $this->json('erreur relation non trouvé');
                }
                $notice->setUserNoted($userNoted);
            } elseif (key_exists(Notice::SALON_NOTED, $json) && isset($json[Notice::SALON_NOTED])) {
                $salonNoted = $this->salonRepository->findOneBy(['id' => $json[Notice::SALON_NOTED]]);
                if(!$salonNoted) {
                    return $this->json('erreur relation non trouvé');
                }
                $notice->setSalonNoted($salonNoted);
            }
            $this->entityManager->persist($notice);
            $this->entityManager->flush();
            return $this->json('cest bon mon reuf', 201);
        } else {
            return $this->json('User non trouvé', 405);
        }
    }
}
