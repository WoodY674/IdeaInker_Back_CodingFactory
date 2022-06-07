<?php

namespace App\Tests\Entity;

use App\Entity\Channel;
use App\Entity\Message;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MessageTest extends KernelTestCase
{
    public function getEntity()
    {
        return (new Message())
            ->setMessage('test')
            ->setSendBy(new User())
            ->setRecipient(new User())
            ->setChannel(new Channel());
    }

    public function assertHasErrors(Message $message, int $numberOfErrors = 0)
    {
        self::bootKernel();
        $error = self::$container->get('validator')->validate($message);
        $this->assertCount($numberOfErrors, $error);
    }

    public function testMessageIsValid()
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }
}
