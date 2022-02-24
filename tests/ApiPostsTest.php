<?php
// tests/AuthenticationTest.php


namespace App\Tests;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Post;
use App\Entity\User;


class ApiPostsTest extends ApiTestCase
{
    public function testGetAllPosts(): void
    {
        $response = static::createClient()->request('GET', '/api/posts');
        //Assert that the returned response is 200
        $this->assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

    }

    public function testGetOneSalon(): void
    {
        $response = static::createClient()->request('GET', '/api/posts/3');
        //Assert that the returned response is 200
        $this->assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

    }

    public function testCreatePost(): void
    {


        $response = static::createClient()->request('POST', '/api/posts', ['json' => [
            'image' => '/api/images/1',
            'createdBy' => '/api/users/12'
        ]]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $jsonContent = $response->getContent();
        print_r($jsonContent);
        $jsonArray = json_decode($jsonContent,true);
        $id = $jsonArray["id"];

        print_r($id);
        $response = static::createClient()->request('DELETE', "/api/posts/$id");

    }
    public function testPutPost(): void
    {
        $response = static::createClient()->request('POST', '/api/posts', ['json' => [
            'image' => '/api/images/1',
            'createdBy' => '/api/users/12'
        ]]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $jsonContent = $response->getContent();
        print_r($jsonContent);
        $jsonArray = json_decode($jsonContent,true);
        $id = $jsonArray["id"];

        $response = static::createClient()->request('PUT', "/api/posts/$id", ['json' => [
            'image' => '/api/images/1',
            'createdBy' => '/api/users/12'
        ]]);

        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');


        // Delete the user we just created

        print_r($id);
        $response = static::createClient()->request('DELETE', "/api/posts/$id");

    }
}