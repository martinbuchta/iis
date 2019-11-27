<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

class SecurityVoter
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var Security
     */
    private $security;

    public function __construct(TokenStorageInterface $tokenStorage, Security $security)
    {
        $this->tokenStorage = $tokenStorage;
        $this->security = $security;
    }

    public function onlyRedaktorOrPokladni()
    {
        $user = $this->tokenStorage->getToken()->getUser();

        if (false == $user instanceof User) {
            throw new AccessDeniedException();
        }

        $roles = $user->getRoles();

        if ((false == $this->security->isGranted("ROLE_REDAKTOR")) && (false == $this->security->isGranted("ROLE_POKLADNI"))) {
            throw new AccessDeniedException();
        }
    }
}
