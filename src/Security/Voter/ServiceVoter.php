<?php

namespace App\Security\Voter;

use App\Entity\Post;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class ServiceVoter extends Voter {

    public const CREATE = 'CREATE';
    public const READ   = 'READ';
    public const EDIT   = 'EDIT';
    public const DELETE = 'DELETE';

    private Security $security;

    public function __construct(Security $security) {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::CREATE, self::READ, self::EDIT, self::DELETE]);
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
        return $this->getResponseByInstance($attribute, $subject, $user);
    }

    private function getResponseByInstance(string $attribute, $subject, $user): bool {

        if ($subject instanceof Post) {
            switch ($attribute) {
                case self::CREATE:
                case self::READ:
                    return $this->security->isGranted("ROLE_USER");
                case self::EDIT:
                    return $this->isOwnerPost($user, $subject);
                case self::DELETE:
                    return $this->security->isGranted("ROLE_ADMIN");
            }
        }

        return false;
    }

    private function isOwnerPost(User $user, Post $post): bool {
        return $user === $post->getCreatedBy();
    }
}
