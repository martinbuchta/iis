<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Ticket
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class Ticket
{
    /**
     * @var int|null
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var float
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @var Seat
     * @ORM\ManyToOne(targetEntity="Seat", inversedBy="tickets")
     * @ORM\JoinColumn(name="seat_id", referencedColumnName="id")
     */
    private $seat;

    /**
     * @var Performance
     * @ORM\ManyToOne(targetEntity="Performance", inversedBy="tickets")
     * @ORM\JoinColumn(name="performance_id", referencedColumnName="id")
     */
    private $performance;

    /**
     * @var Reservation
     * @ORM\ManyToOne(targetEntity="Reservation", inversedBy="tickets")
     * @ORM\JoinColumn(name="reservation_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $reservation;

    public function __construct()
    {
        $this->price = 0.;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     */
    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    /**
     * @return Seat
     */
    public function getSeat(): Seat
    {
        return $this->seat;
    }

    /**
     * @param Seat $seat
     */
    public function setSeat(Seat $seat): void
    {
        $this->seat = $seat;
    }

    /**
     * @return Performance
     */
    public function getPerformance(): Performance
    {
        return $this->performance;
    }

    /**
     * @param Performance $performance
     */
    public function setPerformance(Performance $performance): void
    {
        $this->performance = $performance;
    }

    /**
     * @return Reservation
     */
    public function getReservation(): Reservation
    {
        return $this->reservation;
    }

    /**
     * @param Reservation $reservation
     */
    public function setReservation(Reservation $reservation): void
    {
        $this->reservation = $reservation;
    }
}
