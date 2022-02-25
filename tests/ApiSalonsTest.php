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
        return [
            'auth_bearer' => $json["token"],
            'headers' => [
                'content-type' => 'application/ld+json; charset=utf-8'
            ]
        ];
    }

    public function testGetAllSalons(): void
    {
        $response = static::createClient()->request('GET', '/api/salons',$this->addToken());
        //Assert that the returned response is 200
        $this->assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON-LD (the default)
        //$this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

    }

    public function testGetOneSalon(): void
    {
         static::createClient()->request('GET', '/api/salons/2',$this->addToken());
        //Assert that the returned response is 200
        $this->assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON-LD (the default)
        //$this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

    }

    public function testCreateSalon(): void
    {

        $responseImage = static::createClient()->request('POST', '/api/images', ['json' => [
            'image' => 'https://www.neonmag.fr/imgre/fit/https.3A.2F.2Fi.2Epmdstatic.2Enet.2FNEO.2F2019.2F11.2F24.2F30e037d6-34a5-49ea-ac52-77b7e796342c.2Ejpeg/1170x658/background-color/ffffff/quality/90/7-tatouages-insolites-sur-la-langue.jpg',
        ],$this->addToken()]);

        $jsonContentImage = $responseImage->getContent();
        $jsonArrayImage = json_decode($jsonContentImage,true);
        $idImage = $jsonArrayImage["id"];

        $response = static::createClient()->request('POST', '/api/salons', ['json' => [
            'address' => '8 rue des poissonniers',
            'zipCode' => '75017',
            'city' => 'Paris',
            'manager' => '/api/users/12',
            'salonImage' => "/api/images/$idImage"
        ]]);

        $this->assertResponseStatusCodeSame(201);
        //$this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $jsonContent = $response->getContent();
        $jsonArray = json_decode($jsonContent,true);
        $id = $jsonArray["id"];
        static::createClient()->request('DELETE', "/api/salons/$id",$this->addToken());
        static::createClient()->request('DELETE', "/api/images/$idImage",$this->addToken());
    }
    public function testPutSalon(): void
    {
        $responseImage = static::createClient()->request('POST', '/api/images', ['json' => [
            'image' => 'https://www.neonmag.fr/imgre/fit/https.3A.2F.2Fi.2Epmdstatic.2Enet.2FNEO.2F2019.2F11.2F24.2F30e037d6-34a5-49ea-ac52-77b7e796342c.2Ejpeg/1170x658/background-color/ffffff/quality/90/7-tatouages-insolites-sur-la-langue.jpg',
        ]]);

        $jsonContentImage = $responseImage->getContent();
        $jsonArrayImage = json_decode($jsonContentImage,true);
        $idImage = $jsonArrayImage["id"];

        $response = static::createClient()->request('POST', '/api/salons', ['json' => [
            'address' => '5 rue des poissonniers',
            'zipCode' => '75017',
            'city' => 'Paris',
            'manager' => '/api/users/12',
            'salonImage' => "/api/images/$idImage"
        ],$this->addToken()]);

        $this->assertResponseStatusCodeSame(201);
        //$this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $jsonContent = $response->getContent();
        $jsonArray = json_decode($jsonContent,true);
        $id = $jsonArray["id"];

        static::createClient()->request('PUT', "/api/salons/$id", ['json' => [
            'address' => 'kjbi',
            'zipCode' => '95000',
            'city' => 'Cergy',
            'manager' => '/api/users/12',
            'salonImage' => "/api/images/$idImage"
        ],$this->addToken()]);

        $this->assertResponseStatusCodeSame(200);
        //$this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');


        // Delete the user we just created
        static::createClient()->request('DELETE', "/api/salons/$id",$this->addToken());
        static::createClient()->request('DELETE', "/api/images/$idImage",$this->addToken());
    }
}