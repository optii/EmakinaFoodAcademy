<?php

namespace DamDan\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;

/**
 * Dish
 *
 * @ORM\Table(name="dish")
 * @ORM\Entity(repositoryClass="DamDan\AppBundle\Repository\DishRepository")
 */
class Dish
{
    const STATUS_DRAFT = "Brouillon";
    const STATUS_REFUSED = "Refusé";
    const STATUS_ACCEPTED = "Validé";
    const STATUS_IN_VALIDATION = "En validation";
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=50)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=255)
     */
    private $description;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=50)
     */
    private $status;

    /**
     * @var bool
     *
     * @ORM\Column(name="homeMade", type="boolean")
     */
    private $homeMade;

    /*TODO
    /**
     * One author has many dishes.
     * @ManyToOne(targetEntity="User", inversedBy="dishes")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    //private $author;

    /**
     * Many Users have Many Groups.
     * @ManyToMany(targetEntity="Allergen")
     * @JoinTable(name="dishes_allergens",
     *      joinColumns={@JoinColumn(name="dish_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="allergen_id", referencedColumnName="id")}
     *      )
     */
    private $allergens;

    public function __construct()
    {
        $this->allergens = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return Dish
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Dish
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return Dish
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Dish
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set homeMade
     *
     * @param boolean $homeMade
     *
     * @return Dish
     */
    public function setHomeMade($homeMade)
    {
        $this->homeMade = $homeMade;

        return $this;
    }

    /**
     * Get homeMade
     *
     * @return bool
     */
    public function isHomeMade()
    {
        return $this->homeMade;
    }

    /**
     * Get allergens
     *
     * @return ArrayCollection
     */
     public function getAllergens(){
        return $this->allergens;
     }
}

