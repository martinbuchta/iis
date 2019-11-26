<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Play
 * @ORM\Entity()
 * @ORM\Table()
 */
class Play
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $staring;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $rating;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="Genre", inversedBy="plays")
     * @ORM\JoinTable(name="plays_genres")
     */
    private $genres;

    /**
     * @var Category
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="plays")
     * @ORM\JoinColumn(name="category_id", referencedColumnName="id")
     */
    private $category;

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="Performance", mappedBy="play")
     */
    private $performances;

    public function __construct()
    {
        $this->name = "";
        $this->staring = "";
        $this->image = "";
        $this->rating = 0;
        $this->genres = new ArrayCollection();
        $this->performances = new ArrayCollection();
    }

    /**
     * @return mixed
     */
    public function getId()
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
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    /**
     * @return string
     */
    public function getStaring(): string
    {
        return $this->staring;
    }

    /**
     * @param string $staring
     */
    public function setStaring(string $staring): void
    {
        $this->staring = $staring;
    }

    /**
     * @return int
     */
    public function getRating(): int
    {
        return $this->rating;
    }

    /**
     * @param int $rating
     */
    public function setRating(int $rating): void
    {
        $this->rating = $rating;
    }

    /**
     * @return ArrayCollection
     */
    public function getGenres(): ArrayCollection
    {
        return $this->genres;
    }
}
