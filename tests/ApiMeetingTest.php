<?php
// tests/AuthenticationTest.php


namespace App\Tests;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User;


class ApiMeetingTest extends ApiTestCase
{
    private function addToken(){
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

    public function testGetAllMeetings(): void
    {
        static::createClient()->request('GET', '/api/meetings',$this->addToken());
        //Assert that the returned response is 200
        $this->assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

    }

    public function testGetOneMeeting(): void
    {
        static::createClient()->request('GET', '/api/meetings/2',$this->addToken());
        //Assert that the returned response is 200
        $this->assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

    }

    public function testCreateMeeting(): void
    {
        $response = static::createClient()->request('POST', '/api/meetings', [
            'json' => [
                'startAt' => '2022-02-24T15:03:03.140Z',
                'endAt' => '2022-02-24T16:03:03.140Z'
            ],
            $this->addToken()
        ]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $jsonContent = $response->getContent();
        $jsonArray = json_decode($jsonContent,true);
        $id = $jsonArray["id"];

        static::createClient()->request('DELETE', "/api/meetings/$id",$this->addToken());
    }
    public function testPutMeeting(): void
    {
        $response = static::createClient()->request('POST', '/api/meetings', [
            'json' => [
                'startAt' => '2022-02-24T15:03:03.140Z',
                'endAt' => '2022-02-24T16:03:03.140Z'
            ],
            $this->addToken()
        ]);

        $jsonContent = $response->getContent();
        $jsonArray = json_decode($jsonContent,true);
        $id = $jsonArray["id"];

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        static::createClient()->request('PUT', "/api/meetings/$id", [
            'json' => [
                'startAt' => '2023-02-24T15:03:03.140Z',
                'endAt' => '2023-02-24T16:03:03.140Z'
            ],
            $this->addToken()
        ]);

        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');


        // Delete the user we just created
        print_r($id);
        static::createClient()->request('DELETE', "/api/meetings/$id", $this->addToken());
    }
}