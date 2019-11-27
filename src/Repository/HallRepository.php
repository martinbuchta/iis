<?php

namespace App\Repository;

use App\Entity\Hall;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\DBAL\Connection;

class HallRepository extends ServiceEntityRepository
{
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(ManagerRegistry $registry, Connection $connection)
    {
        parent::__construct($registry, Hall::class);
        $this->connection = $connection;
    }

    public function existOrderToHall(Hall $hall): bool
    {
        $stmt = $this->connection->executeQuery("
                SELECT COUNT(*) AS pocet 
                FROM ticket
                JOIN seat
                JOIN hall
                WHERE hall.id = ?
                ", [$hall->getId()]);
        $row = $stmt->fetch();

        return $row['pocet'] > 0;
    }
}
