<?php

namespace App\Security\Voter;

use App\Entity\Post;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class ServiceVoter extends Voter {
    public const USER   = 'ROLE_USER';
    public const ADMIN  = 'ROLE_ADMIN';

    private Security $security;

    public function __construct(Security $security) {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::USER, self::ADMIN]) && $subject instanceof \App\Entity\Post;
    }

    /**
     * @param string $attribute
     * @param $subject "subject is a entity Post"
     * @param TokenInterface $token
     * @return bool
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        if ($this->security->isGranted("ROLE_ADMIN")) {
            return true;
        }

        if ($subject->getCreatedBy() === null) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::USER:
                return $this->isOwner($user, $subject);
        }
        return false;
    }

    private function isOwner(User $user, Post $post): bool {
        return $user === $post->getCreatedBy();
    }
}
