<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Seat
 *
 * @ORM\Entity()
 * @ORM\Table()
 */
class Seat
{
    /**
     * @var int|null
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $row;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $number;

    /**
     * @var Hall
     * @ORM\ManyToOne(targetEntity="Hall", inversedBy="seats")
     * @ORM\JoinColumn(name="hall_id", referencedColumnName="id")
     */
    private $hall;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getRow(): int
    {
        return $this->row;
    }

    /**
     * @param int $row
     */
    public function setRow(int $row): void
    {
        $this->row = $row;
    }

    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * @param int $number
     */
    public function setNumber(int $number): void
    {
        $this->number = $number;
    }

    /**
     * @return Hall
     */
    public function getHall(): Hall
    {
        return $this->hall;
    }

    /**
     * @param Hall $hall
     */
    public function setHall(Hall $hall): void
    {
        $this->hall = $hall;
    }
}
