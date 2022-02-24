<?php
// tests/AuthenticationTest.php


namespace App\Tests;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Message;


class ApiMessagesTest extends ApiTestCase
{
    public function testGetAllMessages(): void
    {
        $response = static::createClient()->request('GET', '/api/messages');
        //Assert that the returned response is 200
        $this->assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

    }

    public function testGetOneMessage(): void
    {
        $response = static::createClient()->request('GET', '/api/messages/1');
        //Assert that the returned response is 200
        $this->assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

    }

    public function testCreateMessage(): void
    {
        $response = static::createClient()->request('POST', '/api/messages', ['json' => [
            'message' => 'test',
            'sendAt' => '2022-02-24T15:03:03.140Z',
            'sendBy' => '/api/users/12',
            'recipient' => '/api/users/14'
        ]]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $jsonContent = $response->getContent();
        $jsonArray = json_decode($jsonContent,true);
        $id = $jsonArray["id"];

        static::createClient()->request('DELETE', "/api/messages/$id");

    }
    public function testPutMessages(): void
    {
        $response = static::createClient()->request('POST', '/api/messages', ['json' => [
            'message' => 'test',
            'sendAt' => '2022-02-24T15:03:03.140Z',
            'sendBy' => '/api/users/12',
            'recipient' => '/api/users/14'
        ]]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $jsonContent = $response->getContent();
        $jsonArray = json_decode($jsonContent,true);
        $id = $jsonArray["id"];

        static::createClient()->request('PUT', "/api/messages/$id", ['json' => [
            'message' => 'testPUT',
            'sendAt' => '2022-02-24T15:03:03.140Z',
            'sendBy' => '/api/users/12',
            'recipient' => '/api/users/14'
        ]]);

        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');


        // Delete the user we just created

        static::createClient()->request('DELETE', "/api/messages/$id");

    }
}