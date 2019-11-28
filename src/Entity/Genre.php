<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Genre
 *
 * @ORM\Table()
 * @ORM\Entity()
 */
class Genre
{
    /**
     * @var int|null
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="Play", mappedBy="genres")
     */
    private $plays;

    public function __construct()
    {
        $this->name = '';
        $this->plays = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
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
     * @return ArrayCollection
     */
    public function getPlays(): ArrayCollection
    {
        return $this->plays;
    }
}
