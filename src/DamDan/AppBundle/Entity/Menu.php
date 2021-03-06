<?php

namespace DamDan\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Menu
 *
 * @ORM\Table(name="menu")
 * @ORM\Entity(repositoryClass="DamDan\AppBundle\Repository\MenuRepository")
 */
class Menu
{
    /**
     * Status constants
     */
    const STATUS_DRAFT         = "STATUS_DRAFT";
    const STATUS_REFUSED       = "STATUS_REFUSED";
    const STATUS_ACCEPTED      = "STATUS_ACCEPTED";
    const STATUS_IN_VALIDATION = "STATUS_IN_VALIDATION";

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
     * @Assert\NotBlank()
     * @ORM\Column(name="title", type="string", length=100)
     */
    private $title;

    /**
     * @var float
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var int
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="appearance_order", type="integer")
     */
    private $order;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="status", type="string", length=50)
     */
    private $status;

    /**
     * One author has many menus.
     * @ORM\ManyToOne(targetEntity="DamDan\UserBundle\Entity\User", inversedBy="menus")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $author;

    /**
     * Many menus has many dishes.
     * @ORM\ManyToMany(targetEntity="Dish", inversedBy="menus")
     * @ORM\JoinTable(name="dishes_menus")
     */
    private $dishes;

    public function __construct()
    {
        $this->dishes = new ArrayCollection();
        $this->status = self::STATUS_DRAFT;
    }

    /**
     * @return mixed
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param mixed $author
     */
    public function setAuthor($author)
    {
        $this->author = $author;
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
     * @return Menu
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
     * Set price
     *
     * @param float $price
     *
     * @return Menu
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
     * Set Order
     *
     * @param integer $order
     *
     * @return Menu
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get Order
     *
     * @return int
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return Menu
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
     * Get Status Name
     *
     * @return string
     */
    public function getStatusName(){
        return array_flip(self::getStatusArray())[$this->getStatus()];
    }

    /**
     * Get status constants
     * @return array
     */
    public static function getStatusArray()
    {
        return [
            "Draft"         => self::STATUS_DRAFT,
            "Accepted"      => self::STATUS_ACCEPTED,
            "In validation" => self::STATUS_IN_VALIDATION,
            "Refused"       => self::STATUS_REFUSED
        ];
    }

    /**
     * Get Dishes
     *
     * @return ArrayCollection
     */
    public function getDishes()
    {
        return $this->dishes;
    }

    /**
     * Get Dishes by Category
     *
     * @return array
     */
    public function getDishesByCategory(){
        $dishes = $this->getDishes();
        $result = array_fill_keys(array_keys(Dish::getCategoriesArray()), []);

        foreach($dishes as $dish){
            $result[$dish->getCategoryName()][] = $dish;
        }

        return $result;
    }

    /**
     * @param ArrayCollection $dishes
     */
    public function setDishes(ArrayCollection $dishes)
    {
        $this->dishes = $dishes;
    }

    /**
     * @param Dish $dish
     */
     public function addDish(Dish $dish)
     {
        $this->dishes->add($dish);
     }

     public function __toString()
     {
         return $this->getTitle();
     }
}
