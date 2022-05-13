<?php

namespace App\Tests\Entity;


use App\Entity\Channel;
use App\Entity\Message;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ChannelTest extends KernelTestCase
{
    public function getEntity(){
        return (new Channel())
            ->addUsersInside(new User())
            ->addMessage(new Message());
    }

    public function assertHasErrors(Channel $channel, int $numberOfErrors = 0){
        self::bootKernel();
        $error = self::$container->get('validator')->validate($channel);
        $this->assertCount($numberOfErrors, $error);
    }

    public function testChannelIsValid(){
        $this->assertHasErrors($this->getEntity(), 0);
    }

}