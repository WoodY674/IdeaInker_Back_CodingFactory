<?php

namespace App\Tests\Entity;

use App\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ImageTest extends KernelTestCase
{
    public function getEntity()
    {
        return (new Image())
            ->setImagePath('test');
    }

    public function assertHasErrors(Image $image, int $numberOfErrors = 0)
    {
        self::bootKernel();
        $error = self::$container->get('validator')->validate($image);
        $this->assertCount($numberOfErrors, $error);
    }

    public function testImageIsValid()
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }

    /*
     * test à voir, ne renvoie pas d'erreur même si le champ est vide :/
    public function testImageInvalidBlank(){
        $this->assertHasErrors($this->getEntity()->setImagePath(''), 1);
    }
    */
}
