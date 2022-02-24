<?php
// tests/AuthenticationTest.php


namespace App\Tests;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\Post;
use App\Entity\User;
use App\Entity\Image;



class ApiImageTest extends ApiTestCase
{
    public function testGetAllImages(): void
    {
        static::createClient()->request('GET', '/api/images');
        //Assert that the returned response is 200
        $this->assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

    }

    public function testGetOneSalon(): void
    {
        static::createClient()->request('GET', '/api/images/1');
        //Assert that the returned response is 200
        $this->assertResponseIsSuccessful();
        // Asserts that the returned content type is JSON-LD (the default)
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

    }

    public function testCreateImage(): void
    {

        $response = static::createClient()->request('POST', '/api/images', ['json' => [
            'image' => 'https://www.neonmag.fr/imgre/fit/https.3A.2F.2Fi.2Epmdstatic.2Enet.2FNEO.2F2019.2F11.2F24.2F30e037d6-34a5-49ea-ac52-77b7e796342c.2Ejpeg/1170x658/background-color/ffffff/quality/90/7-tatouages-insolites-sur-la-langue.jpg',
        ]]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $jsonContent = $response->getContent();
        print_r($jsonContent);
        $jsonArray = json_decode($jsonContent,true);
        $id = $jsonArray["id"];

        print_r($id);
        static::createClient()->request('DELETE', "/api/images/$id");

    }
    public function testPutImage(): void
    {
        $response = static::createClient()->request('POST', '/api/images', ['json' => [
            'image' => 'https://www.neonmag.fr/imgre/fit/https.3A.2F.2Fi.2Epmdstatic.2Enet.2FNEO.2F2019.2F11.2F24.2F30e037d6-34a5-49ea-ac52-77b7e796342c.2Ejpeg/1170x658/background-color/ffffff/quality/90/7-tatouages-insolites-sur-la-langue.jpg',
        ]]);

        $this->assertResponseStatusCodeSame(201);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');

        $jsonContent = $response->getContent();
        print_r($jsonContent);
        $jsonArray = json_decode($jsonContent,true);
        $id = $jsonArray["id"];

        static::createClient()->request('PUT', "/api/images/$id", ['json' => [
            'image' => 'https://sf1.bibamagazine.fr/wp-content/uploads/biba/2019/04/tendance-tatouage-effet-broderie-cote-sur-les-reseaux-sociaux.jpg',
        ]]);

        $this->assertResponseStatusCodeSame(200);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');


        // Delete the user we just created

        print_r($id);
        static::createClient()->request('DELETE', "/api/images/$id");

    }
}