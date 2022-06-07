<?php

namespace App\Tests\Entity;

use App\Entity\CoordinateStore;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CoordinateStoreTest extends KernelTestCase
{
    public function getEntity()
    {
        return (new CoordinateStore())
            ->setCompany('test')
            ->setLatitude('test')
            ->setLongitude('test');
    }

    public function assertHasErrors(CoordinateStore $coordinateStore, int $numberOfErrors = 0)
    {
        self::bootKernel();
        $error = self::$container->get('validator')->validate($coordinateStore);
        $this->assertCount($numberOfErrors, $error);
    }

    public function testCoordinateStoreIsValid()
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testCoordinateStoreCompanyIsInvalidBlank()
    {
        $this->assertHasErrors($this->getEntity()->setCompany(''), 2);
    }

    public function testCoordinateStoreLatitudeIsInvalidBlank()
    {
        $this->assertHasErrors($this->getEntity()->setLatitude(''), 1);
    }

    public function testCoordinateStoreLongitudeIsInvalidBlank()
    {
        $this->assertHasErrors($this->getEntity()->setLongitude(''), 1);
    }
}
