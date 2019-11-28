<?php

namespace App\Repository;

use App\Entity\Performance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class PerformanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Performance::class);
    }

    public function findAllFutures()
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.time > :time')
            ->setParameter('time', new \DateTime())
            ->getQuery()
            ->getResult();
    }
}
