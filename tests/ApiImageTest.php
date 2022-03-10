<?php
// tests/AuthenticationTest.php


namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Post;
use App\Entity\User;
use App\Entity\Image;


class ApiImageTest extends ApiTestCase
{
    private function addToken()
    {
        $response = self::createClient()->request('POST', '/authentication_token', [
            'headers' => [
                'Content-Type' => 'application/json'
            ],
            'json' => [
                'email' => 'test4@test.com',
                'password' => 'password',
            ],
        ]);

        $json = $response->toArray();
        return [
            'auth_bearer' => $json["token"],
            'headers' => [
                'Content-Type' => "application/json"
            ]
        ];
    }

    public function testGetAllImages(): void
    {
        static::createClient()->request('GET', '/api/images', $this->addToken());
        //Assert that the returned response is 200
        $this->assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON-LD (the default)
        //$this->assertResponseHeaderSame('Content-Type', 'application/ld+json; charset=utf-8');

    }

    public function testGetOneImage(): void
    {
        static::createClient()->request('GET', '/api/images/2', $this->addToken());
        //Assert that the returned response is 200
        $this->assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON-LD (the default)
        //$this->assertResponseHeaderSame('Content-Type', 'application/ld+json; charset=utf-8');

    }

    public function testCreateImage(): void
    {

        $response = static::createClient()->request('POST', '/api/images', [
                'json' => [
                    'image' => 'langue.jpg',
                ],
                $this->addToken(),
            ]
        );

        $this->assertResponseStatusCodeSame(201);
        //$this->assertResponseHeaderSame('Content-Type', 'application/ld+json; charset=utf-8');

        $jsonContent = $response->getContent();
        $jsonArray = json_decode($jsonContent, true);
        $id = $jsonArray["id"];

        static::createClient()->request('DELETE', "/api/images/$id", $this->addToken());

    }

    public function testPutImage(): void
    {
        $response = static::createClient()->request('POST', '/api/images', [
            'json' => [
                'image' => 'https://www.neonmag.fr/imgre/fit/https.3A.2F.2Fi.2Epmdstatic.2Enet.2FNEO.2F2019.2F11.2F24.2F30e037d6-34a5-49ea-ac52-77b7e796342c.2Ejpeg/1170x658/background-color/ffffff/quality/90/7-tatouages-insolites-sur-la-langue.jpg',
            ],
            $this->addToken()
        ]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('Content-Type', 'application/ld+json; charset=utf-8');

        $jsonContent = $response->getContent();
        $jsonArray = json_decode($jsonContent, true);
        $id = $jsonArray["id"];

        static::createClient()->request('PUT', "/api/images/$id", [
            'json' => [
                'image' => 'https://sf1.bibamagazine.fr/wp-content/uploads/biba/2019/04/tendance-tatouage-effet-broderie-cote-sur-les-reseaux-sociaux.jpg',
            ],
            $this->addToken(),
        ]);

        $this->assertResponseStatusCodeSame(200);
        //$this->assertResponseHeaderSame('Content-Type', 'application/ld+json; charset=utf-8');


        // Delete the user we just created
        static::createClient()->request('DELETE', "/api/images/$id", $this->addToken());
    }
}