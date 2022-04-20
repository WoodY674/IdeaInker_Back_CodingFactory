<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api2/user')]
class ApiUserController extends AbstractController {

    #[Route('/me', name: 'me', methods: ['GET'])]
    public function userMe(): Response {
        dd($this->getUser());
        return "test";
    }
}