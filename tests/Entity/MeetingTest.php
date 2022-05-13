<?php

namespace App\Tests\Entity;

use App\Entity\Meeting;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class MeetingTest extends KernelTestCase
{
    public function getEntity(){
        return (new Meeting())
            ->setStartAt(new \DateTimeImmutable())
            ->setEndAt(new \DateTimeImmutable());
    }

    public function assertHasErrors(Meeting $meeting, int $numberOfErrors = 0){
        self::bootKernel();
        $error = self::$container->get('validator')->validate($meeting);
        $this->assertCount($numberOfErrors, $error);
    }

    public function testMeetingIsValid(){
        $this->assertHasErrors($this->getEntity(), 0);
    }


}