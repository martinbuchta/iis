<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Hall
 *
 * @ORM\Table()
 * @ORM\Entity()
 * @UniqueEntity(fields={"name"}, message="Sál s tímto názvem již existuje.")
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
     * @var string
     * @ORM\Column(type="string", length=60, unique=true)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var int
     * @ORM\Column(type="integer")
     * @Assert\GreaterThan(0)
     */
    private $rowCount;

    /**
     * @var int
     * @ORM\Column(type="integer")
     * @Assert\GreaterThan(0)
     */
    private $seatsInRow;

    /**
     * @var string
     * @ORM\Column(type="text")
     * @Assert\NotBlank()
     */
    private $address;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Performance", mappedBy="hall")
     */
    private $performances;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Seat", mappedBy="hall")
     */
    private $seats;

    public function __construct()
    {
        $this->performances = new ArrayCollection();
        $this->rowCount = 0;
        $this->seatsInRow = 0;
        $this->address = "";
        $this->name = "";
        $this->seats = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
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

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return array
     */
    public function getPerformances(): array
    {
        return $this->performances->toArray();
    }
}
