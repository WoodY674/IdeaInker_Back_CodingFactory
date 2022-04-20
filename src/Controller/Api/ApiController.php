<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api2', name: 'api_')]
class ApiController extends AbstractController
{
    /*
    #[Route('/', name: 'api')]
    public function index(): Response
    {
        $route = [
            'Post' => [
                'GET: all' => [
                    'route' => '/post/',
                    'args' => 'no args'
                ],
                'GET: one' => [
                    'route' => 'post/{id}',
                    'args' => 'Id post in url'
                ],
                'POST: create' => [
                    'route' => '/post/',
                    'args' => '{ userId: id, image: "url", content: "text" }'
                ],
                'DELETE: delete' => [
                    'route' => 'post/{id}',
                    'args' => 'Id post in url'
                ]
            ],
            'Salon' => [
                'GET: all' => [
                    'route' => '/salon/',
                    'args' => 'no args'
                ],
                'GET: one' => [
                    'route' => '/salon/{id}',
                    'args' => 'Id salon in url'
                ],
                'POST: create' => [
                    'route' => '/salon/',
                    'args' => "{ address: 'text', city: 'text', zipCode: 'text', manager: 'userId', image: 'url' }"
                ],
                'DELETE: delete' => [
                    'route' => '/salon/{id}',
                    'args' => 'Id salon in url'
                ]
            ],
        ];
        return $this->render('api/index.html.twig', [
            'api_route' => $route,
        ]);
    }*/
}
