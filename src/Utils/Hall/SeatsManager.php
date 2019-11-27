<?php

namespace App\Utils\Hall;

use App\Entity\Hall;
use App\Entity\Seat;
use App\Repository\SeatRepository;
use Doctrine\ORM\EntityManagerInterface;

class SeatsManager
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var SeatRepository
     */
    private $seatRepository;

    public function __construct(EntityManagerInterface $entityManager, SeatRepository $seatRepository)
    {
        $this->entityManager = $entityManager;
        $this->seatRepository = $seatRepository;
    }

    public function resizeHall(Hall $hall): void
    {
        $seats = $this->seatRepository->findAllByHall($hall);
        $doneRows = 0;
        $doneSeats = 0;

        // delete seats if it should be deleted
        foreach ($seats as $seat) {
            if ($seat->getRow() > $hall->getRowCount() || $seat->getNumber() > $hall->getSeatsInRow()) {
                $this->entityManager->remove($seat);
            } else {
                if ($seat->getRow() > $doneRows) {
                    $doneRows = $seat->getRow();
                }
                if ($seat->getNumber() > $doneSeats) {
                    $doneSeats = $seat->getNumber();
                }
            }
        }

        // create new seats
        for ($x = 1; $x <= $hall->getSeatsInRow(); $x++) {
            for ($y = 1; $y <= $hall->getRowCount(); $y++) {
                if ($x <= $doneSeats && $y <= $doneRows) {
                    continue;
                }

                $seat = new Seat($hall);
                $seat->setRow($y);
                $seat->setNumber($x);
                $this->entityManager->persist($seat);
            }
        }

        $this->entityManager->flush();
    }
}
