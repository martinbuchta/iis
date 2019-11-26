<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Hall
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class Hall
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $rowCount;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $seatsInRow;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $address;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Performance", mappedBy="hall")
     */
    private $performances;

    public function __construct()
    {
        $this->performances = new ArrayCollection();
        $this->rowCount = 0;
        $this->seatsInRow = 0;
        $this->address = "";
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getRowCount(): int
    {
        return $this->rowCount;
    }

    /**
     * @param int $rowCount
     */
    public function setRowCount(int $rowCount): void
    {
        $this->rowCount = $rowCount;
    }

    /**
     * @return int
     */
    public function getSeatsInRow(): int
    {
        return $this->seatsInRow;
    }

    /**
     * @param int $seatsInRow
     */
    public function setSeatsInRow(int $seatsInRow): void
    {
        $this->seatsInRow = $seatsInRow;
    }

    /**
     * @return string
     */
    public function getAddress(): string
    {
        return $this->address;
    }

    /**
     * @param string $address
     */
    public function setAddress(string $address): void
    {
        $this->address = $address;
    }
}
