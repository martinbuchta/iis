<?php

namespace App\Security;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordChanger
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, EntityManagerInterface $entityManager)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
    }

    public function changePassword(User $user, string $plainPassword, bool $flush = true): void
    {
        $encoded = $this->passwordEncoder->encodePassword($user, $plainPassword);
        $user->setPassword($encoded);

        if ($flush) {
            $this->entityManager->flush();
        }
    }
}