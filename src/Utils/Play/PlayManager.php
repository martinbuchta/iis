<?php

namespace App\Utils\Play;

use App\Entity\Play;
use Doctrine\ORM\EntityManagerInterface;

class PlayManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function add(Play $play)
    {
        $this->entityManager->persist($play);
        $this->entityManager->flush();
    }
}
