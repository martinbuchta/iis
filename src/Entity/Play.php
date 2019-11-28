<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Play
 * @ORM\Entity()
 * @ORM\Table()
 * @UniqueEntity(fields={"name"}, message="Inscenace s tímto jménem již existuje.")
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
     * @ORM\Column(type="string", length=100, unique=true)
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $image;

    /**
     * @var string|null
     * @ORM\Column(type="text", nullable=true)
     */
    private $staring;

    /**
     * @var int
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *      min = 1,
     *      max = 5,
     *      notInRangeMessage = "Hodnota musí být mezi 1 až 5."
     * )
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
        $this->image = "";
        $this->rating = 1;
        $this->genres = new ArrayCollection();
        $this->performances = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
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
     * @return string|null
     */
    public function getStaring(): ?string
    {
        return $this->staring;
    }

    /**
     * @param string|null $staring
     */
    public function setStaring(?string $staring): void
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
    public function getGenres(): array
    {
        return $this->genres->toArray();
    }

    public function addGenre(Genre $genre)
    {
        $this->genres->add($genre);
    }

    public function removeGenre(Genre $genre)
    {
        $this->genres->removeElement($genre);
    }

    /**
     * @return Category|null
     */
    public function getCategory(): ?Category
    {
        return $this->category;
    }

    /**
     * @param Category $category
     */
    public function setCategory(Category $category): void
    {
        $this->category = $category;
    }

    /**
     * @return array
     */
    public function getPerformances(): array
    {
        return $this->performances->toArray();
    }
}
