<?php
// tests/AuthenticationTest.php


namespace App\Tests;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Salons;


class ApiSalonsTest extends ApiTestCase
{
    private function addToken()
    {
        $client = self::createClient();
        // retrieve a token
        $response = $client->request('POST', '/authentication_token', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => 'test1@test.com',
                'password' => 'password',
            ],
        ]);

        $json = $response->toArray();
        return ['auth_bearer' => $json["token"]];
    }

    public function testGetAllSalons(): void
    {
        $response = static::createClient()->request('GET', '/api/salons',$this->addToken());
        //Assert that the returned response is 200
        $this->assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

    }

    public function testGetOneSalon(): void
    {
        $response = static::createClient()->request('GET', '/api/salons/2',$this->addToken());
        //Assert that the returned response is 200
        $this->assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

    }

    public function testCreateSalon(): void
    {
        $response = static::createClient()->request('POST', '/api/salons', [
            'json' => [
                'address' => '2 rue des poissonniers',
                'zipCode' => '75017',
                'city' => 'Paris',
                'manager' => '/api/users/12',
                'salonImage' => '/api/images/73'
            ],
            $this->addToken()
        ]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $jsonContent = $response->getContent();
        $jsonArray = json_decode($jsonContent,true);
        $id = $jsonArray["id"];
        static::createClient()->request('DELETE', "/api/salons/$id",$this->addToken());

    }
    public function testPutUser(): void
    {
        $response = static::createClient()->request('POST', '/api/salons', [
            'json' => [
                'address' => '2 rue des poissonniers',
                'zipCode' => '75017',
                'city' => 'Paris',
                'manager' => '/api/users/12',
                'salonImage' => '/api/images/74'
            ],
            $this->addToken()
        ]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $jsonContent = $response->getContent();
        print_r($jsonContent);
        $jsonArray = json_decode($jsonContent,true);
        $id = $jsonArray["id"];

        static::createClient()->request('PUT', "/api/salons/$id", [
            'json' => [
                'address' => 'kjbi',
                'zipCode' => '95000',
                'city' => 'Cergy',
                'manager' => '/api/users/12',
                'salonImage' => '/api/images/75'
            ],
            $this->addToken()
        ]);

        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');


        // Delete the user we just created

        print_r($id);
        $response = static::createClient()->request('DELETE', "/api/salons/$id",$this->addToken());

    }
}