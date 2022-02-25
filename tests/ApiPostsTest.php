<?php
// tests/AuthenticationTest.php


namespace App\Tests;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Post;
use App\Entity\User;


class ApiPostsTest extends ApiTestCase
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

    public function testGetAllPosts(): void
    {
        $response = static::createClient()->request('GET', '/api/posts',$this->addToken());
        //Assert that the returned response is 200
        $this->assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON-LD (the default)
        //$this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

    }

    public function testGetOnePost(): void
    {
        $response = static::createClient()->request('GET', '/api/posts/3', $this->addToken());
        //Assert that the returned response is 200
        $this->assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON-LD (the default)
        //$this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

    }

    public function testCreatePost(): void
    {


        $response = static::createClient()->request('POST', '/api/posts', ['json' => [
            'image' => '/api/images/1',
            'createdBy' => '/api/users/12'
        ], $this->addToken()]);

        $this->assertResponseStatusCodeSame(201);
        //$this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $jsonContent = $response->getContent();
        $jsonArray = json_decode($jsonContent,true);
        $id = $jsonArray["id"];
        static::createClient()->request('DELETE', "/api/posts/$id", $this->addToken());

    }
    public function testPutPost(): void
    {
        $response = static::createClient()->request('POST', '/api/posts', ['json' => [
            'image' => '/api/images/1',
            'createdBy' => '/api/users/12'
        ],$this->addToken()]);

        $this->assertResponseStatusCodeSame(201);
        //$this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $jsonContent = $response->getContent();
        $jsonArray = json_decode($jsonContent,true);
        $id = $jsonArray["id"];

        static::createClient()->request('PUT', "/api/posts/$id", ['json' => [
            'image' => '/api/images/1',
            'createdBy' => '/api/users/12'
        ], $this->addToken()]);

        $this->assertResponseStatusCodeSame(200);
        //$this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');


        // Delete the user we just created
        static::createClient()->request('DELETE', "/api/posts/$id",$this->addToken());
    }
}