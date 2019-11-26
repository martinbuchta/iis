<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Performance
 * @ORM\Table()
 * @ORM\Entity()
 */
class Performance
{
    /**
     * @var null|int
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private $time;

    /**
     * @var float
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @var Play
     * @ORM\ManyToOne(targetEntity="Play", inversedBy="performances")
     * @ORM\JoinColumn(name="play_id", referencedColumnName="id")
     */
    private $play;

    /**
     * @var Hall
     * @ORM\ManyToOne(targetEntity="Hall", inversedBy="performances")
     * @ORM\JoinColumn(name="hall_id", referencedColumnName="id")
     */
    private $hall;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Ticket", mappedBy="performance")
     */
    private $tickets;

    public function __construct()
    {
        $this->time = new \DateTime();
        $this->price = 0.;
        $this->tickets = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getTime(): \DateTime
    {
        return $this->time;
    }

    /**
     * @param \DateTime $time
     */
    public function setTime(\DateTime $time): void
    {
        $this->time = $time;
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
     * @return Play
     */
    public function getPlay(): Play
    {
        return $this->play;
    }

    /**
     * @param Play $play
     */
    public function setPlay(Play $play): void
    {
        $this->play = $play;
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
