<?php
// tests/AuthenticationTest.php


namespace App\Tests;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;


class ApiUserTest extends ApiTestCase
{
    public function testGetAllUsers(): void
    {
        $response = static::createClient()->request('GET', '/api/users');
        //Assert that the returned response is 200
        $this->assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

    }

    public function testGetOneUsers(): void
    {
        $response = static::createClient()->request('GET', '/api/users/12');
        //Assert that the returned response is 200
        $this->assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

    }

    public function testCreateUser(): void
    {
        $response = static::createClient()->request('POST', '/api/users', ['json' => [
            'email' => 'test@test.com',
            'password' => 'test'
        ]]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $jsonContent = $response->getContent();
        print_r($jsonContent);
        $jsonArray = json_decode($jsonContent,true);
        $id = $jsonArray["id"];

        print_r($id);
        $response = static::createClient()->request('DELETE', "/api/users/$id");

    }
    public function testPutUser(): void
    {
        $response = static::createClient()->request('POST', '/api/users', ['json' => [
            'email' => 'test@test.com',
            'password' => 'test'
        ]]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $jsonContent = $response->getContent();
        print_r($jsonContent);
        $jsonArray = json_decode($jsonContent,true);
        $id = $jsonArray["id"];

        $response = static::createClient()->request('PUT', "/api/users/$id", ['json' => [
            'email' => 'testPUT@test.com',
            'password' => 'testPUT'
        ]]);

        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');


        // Delete the user we just created

        print_r($id);
        $response = static::createClient()->request('DELETE', "/api/users/$id");

    }
}