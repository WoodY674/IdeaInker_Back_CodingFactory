<?php
// tests/AuthenticationTest.php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;


class AuthenticationTest extends ApiTestCase
{
    //use ReloadDatabaseTrait;


    public function testAuthSuccessful(): void
    {
        $client = self::createClient();

        // retrieve a token
        $response = $client->request('POST', '/authentication_token', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => 'test@bg.fr',
                'password' => 'bg',
            ],
        ]);

        $json = $response->toArray();
        $this->assertResponseIsSuccessful();
        $this->assertArrayHasKey('token', $json);
    }



  

}