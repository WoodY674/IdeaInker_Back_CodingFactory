<?php

namespace App\Tests\Entity;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    public function getEntity()
    {
        return (new User())
            ->setEmail('test@test.com')
            ->setPassword('test123?')
            ->setPseudo('Test67');
    }

    public function assertHasErrors(User $user, int $numberOfErrors = 0)
    {
        self::bootKernel();
        $error = self::$container->get('validator')->validate($user);
        $this->assertCount($numberOfErrors, $error);
    }

    public function testUserIsValid()
    {
        $this->assertHasErrors($this->getEntity(), 0);
    }

    public function testUserIsInvalidWrongEmail()
    {
        $this->assertHasErrors($this->getEntity()->setEmail('ez'), 1);
    }

    public function testUserIsInvalidEmailBlank()
    {
        $this->assertHasErrors($this->getEntity()->setEmail(''), 2);
    }
}
