<?php

namespace App\Controller;

use App\Entity\FeedbackUserData;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    #[Route('/download',name: 'download')]
    public function download(EntityManagerInterface $entityManager)
    {
        $feedbackData = new FeedbackUserData();
        $feedbackData->setEmail($_POST['email']);
        $entityManager->persist($feedbackData);
        $entityManager->flush();
        return $this->render('home/index.html.twig');
    }
}
