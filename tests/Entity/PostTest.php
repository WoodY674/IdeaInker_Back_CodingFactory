<?php

namespace App\Tests\Entity;

use App\Entity\Image;
use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PostTest extends KernelTestCase
{

    public function getEntity(){
        return (new Post())
            ->setImage(new Image())
            ->setContent('test');
    }

    public function assertHasErrors(Post $post, int $numberOfErrors = 0){
        self::bootKernel();
        $error = self::$container->get('validator')->validate($post);
        $this->assertCount($numberOfErrors, $error);
    }

    public function testPostIsValid(){

        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testPostContentIsInValid(){

        $this->assertHasErrors($this->getEntity()->setContent('ez'), 1);
    }


}