<?php

namespace App\Tests\Entity;

use App\Entity\Salon;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class SalonTest extends KernelTestCase
{
    public function getEntity()
    {
        return (new Salon())
            ->setName('test')
            ->setAddress('test')
            ->setZipCode('95000')
            ->setCity('Cergy')
            ->setLatitude('12')
            ->setLongitude('12')
            ->setManager(new User());
    }

    public function assertHasErrors(Salon $salon, int $numberOfErrors = 0)
    {
        self::bootKernel();
        $error = self::$container->get('validator')->validate($salon);
        $this->assertCount($numberOfErrors, $error);
    }

    public function testSalonIsValid()
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testSalonIsInvalidNameTooShort()
    {
        $this->assertHasErrors($this->getEntity()->setName('ez'), 1);
    }

    public function testSalonIsInvalidNameBlank()
    {
        // We expect 2 errors because the name is too short AND it's blank
        $this->assertHasErrors($this->getEntity()->setName(''), 2);
    }

    public function testSalonIsInvalidBlankAddressBlank()
    {
        $this->assertHasErrors($this->getEntity()->setAddress(''), 1);
    }

    public function testSalonIsInvalidZipCodeBlank()
    {
        // We expect 2 errors because the ZipCode is too short AND it's blank
        $this->assertHasErrors($this->getEntity()->setZipCode(''), 2);
    }

    public function testSalonIsInvalidZipCode()
    {
        $this->assertHasErrors($this->getEntity()->setZipCode('950'), 1);
    }

    public function testSalonIsInvalidCityBlank()
    {
        $this->assertHasErrors($this->getEntity()->setCity(''), 1);
    }

    public function testSalonIsInvalidLatitudeBLank()
    {
        $this->assertHasErrors($this->getEntity()->setLatitude(''), 1);
    }

    public function testSalonIsInvalidLongitudeBLank()
    {
        $this->assertHasErrors($this->getEntity()->setLongitude(''), 1);
    }
}
