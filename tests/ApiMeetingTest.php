<?php
// tests/AuthenticationTest.php


namespace App\Tests;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;


class ApiMeetingTest extends ApiTestCase
{
    public function testGetAllMeetings(): void
    {
        static::createClient()->request('GET', '/api/meetings');
        //Assert that the returned response is 200
        $this->assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

    }

    public function testGetOneMeeting(): void
    {
        static::createClient()->request('GET', '/api/meetings/2');
        //Assert that the returned response is 200
        $this->assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

    }

    public function testCreateMeeting(): void
    {
        $response = static::createClient()->request('POST', '/api/meetings', ['json' => [
            'startAt' => '2022-02-24T15:03:03.140Z',
            'endAt' => '2022-02-24T16:03:03.140Z'
        ]]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $jsonContent = $response->getContent();
        print_r($jsonContent);
        $jsonArray = json_decode($jsonContent,true);
        $id = $jsonArray["id"];


        print_r($id);
        static::createClient()->request('DELETE', "/api/meetings/$id");

    }
    public function testPutMeeting(): void
    {
        $response = static::createClient()->request('POST', '/api/meetings', ['json' => [
            'startAt' => '2022-02-24T15:03:03.140Z',
            'endAt' => '2022-02-24T16:03:03.140Z'
        ]]);

        $jsonContent = $response->getContent();
        print_r($jsonContent);
        $jsonArray = json_decode($jsonContent,true);
        $id = $jsonArray["id"];

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');



        static::createClient()->request('PUT', "/api/meetings/$id", ['json' => [
            'startAt' => '2023-02-24T15:03:03.140Z',
            'endAt' => '2023-02-24T16:03:03.140Z'
        ]]);

        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');


        // Delete the user we just created

        print_r($id);
        static::createClient()->request('DELETE', "/api/meetings/$id");

    }
}