<?php

namespace App\Utils;

use App\Entity\Performance;
use App\Entity\Reservation;
use App\Entity\Ticket;
use App\Repository\PerformanceRepository;
use Doctrine\ORM\EntityManagerInterface;

class ReservationCreator
{
    /**
     * @var PerformanceRepository
     */
    private $performanceRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(PerformanceRepository $performanceRepository, EntityManagerInterface $entityManager)
    {
        $this->performanceRepository = $performanceRepository;
        $this->entityManager = $entityManager;
    }

    public function areSeatsAvailable(array $seats, Performance $performance): bool
    {
        $orderedSeats = $this->performanceRepository->getAllOrderedSeats($performance);

        foreach ($orderedSeats as $orderedSeat) {
            if (in_array($orderedSeat, $seats)) {
                return false;
            }
        }

        return true;
    }

    public function createReservation(Reservation $reservation, array $seats, Performance $performance, bool $flush = true)
    {
        $this->entityManager->persist($reservation);
        $this->entityManager->flush();

        foreach ($seats as $seat) {
            $ticket = new Ticket();
            $ticket->setPrice($performance->getPrice());
            $ticket->setPerformance($performance);
            $ticket->setReservation($reservation);
            $ticket->setSeat($seat);
            $this->entityManager->persist($ticket);
        }

        if ($flush) {
            $this->entityManager->flush();
        }
    }

    public function addSeatsToReservations(Reservation $reservation, array $seats)
    {
        foreach ($seats as $seat) {
            $ticket = new Ticket();
            $ticket->setPrice($reservation->getTickets()[0]->getPerformance()->getPrice());
            $ticket->setPerformance($reservation->getTickets()[0]->getPerformance());
            $ticket->setReservation($reservation);
            $ticket->setSeat($seat);
            $this->entityManager->persist($ticket);
        }

        $this->entityManager->flush();
    }
}
