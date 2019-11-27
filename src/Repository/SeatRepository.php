<?php

namespace App\Repository;

use App\Entity\Hall;
use App\Entity\Seat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class SeatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Seat::class);
    }

    public function findAllByHall(Hall $hall)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.hall = :hall')
            ->setParameter('hall', $hall)
            ->getQuery()
            ->getResult();
    }
}
