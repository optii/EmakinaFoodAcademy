<?php

namespace DamDan\AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


/**
 * Dish
 *
 * @ORM\Table(name="dish")
 * @ORM\Entity(repositoryClass="DamDan\AppBundle\Repository\DishRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Dish
{
    /**
     * Status constants
     */
    const STATUS_DRAFT = "STATUS_DRAFT";
    const STATUS_REFUSED = "STATUS_REFUSED";
    const STATUS_ACCEPTED = "STATUS_ACCEPTED";
    const STATUS_IN_VALIDATION = "STATUS_IN_VALIDATION";

    /**
     * Categories constants
     */
    const CATEGORY_STARTER = "CATEGORY_STARTER";
    const CATEGORY_MAIN_COURSE = "CATEGORY_MAIN_COURSE";
    const CATEGORY_CHEESE = "CATEGORY_CHEESE";
    const CATEGORY_DESSERT = "CATEGORY_DESSERT";

    /**
     * @Assert\File(mimeTypes={ "image/jpeg", "image/png", "image/gif" })
     */
    private $file;

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
     * @ORM\Column(name="title", type="string", length=50)
     */
    private $title;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var float
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="status", type="string", length=50)
     */
    private $status;

    /**
     * @var bool
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="homeMade", type="boolean")
     */
    private $homeMade;

    /**
     * Many Features have One Product.
     * @ORM\ManyToOne(targetEntity="DamDan\UserBundle\Entity\User", inversedBy="dishes")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255)
     */
    private $image;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="category", type="string", length=100)
     */
    private $category;

    /**
     * Many Users have Many Groups.
     * @ORM\ManyToMany(targetEntity="Allergen", cascade={"persist"})
     */
    private $allergens;

    /**
     * Many menus has many dishes.
     * @ORM\ManyToMany(targetEntity="Menu", mappedBy="dishes")
     */
    private $menus;

    /**
     * @var \DateTime
     *
     * @ORM\COlumn(name="updated_at", type="datetime")
     */
    private $updatedAt;

    public function __construct()
    {
        $this->allergens = new ArrayCollection();
        $this->menus = new ArrayCollection();
        $this->status = self::STATUS_DRAFT;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     * @return $this
     */
    public function setFile($file)
    {
        $this->file = $file;
        $this->setUpdatedAt(new \DateTime());
        return $this;
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
     * Get Status Name
     *
     * @return string
     */
    public function getStatusName()
    {
        return array_flip(self::getStatusArray())[$this->getStatus()];
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
    public function getAllergens()
    {
        return $this->allergens;
    }

    /**
     * Add Allergen
     *
     * @param Allergen $allergen
     * @return $this
     */
    public function addAllergen(Allergen $allergen){
        $this->allergens->add($allergen);
        return $this;
    }

    /**
     * Remove Allergen
     *
     * @param Allergen $allergen
     * @return $this
     */
    public function removeAllergen(Allergen $allergen){
        $this->allergens->removeElement($allergen);
        return $this;
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
     * @return ArrayCollection
     */
    public function getMenus()
    {
        return $this->menus;
    }

    /**
     * @param ArrayCollection $menus
     */
    public function setMenus($menus)
    {
        $this->menus = $menus;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage($image)
    {
        $this->image = $image;
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param string $category
     */
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * Get Category Name
     *
     * @return string
     */
    public function getCategoryName()
    {
        return array_flip(self::getCategoriesArray())[$this->getCategory()];
    }

    /**
     * Get the color for the category
     *
     * @return string
     */
    public function getCategoryColor()
    {
        return self::getCategoriesColors()[$this->getCategory()];
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     * @return $this
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @ORM\PreUpdate()
     * @ORM\PrePersist()
     */
    public function preUpdate(){
        $this->setUpdatedAt(new \DateTime());
    }

    /**
     * Get status
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
     * Get colors associated to categories
     * @return array
     */
    public static function getCategoriesColors()
    {
        return [
            self::CATEGORY_STARTER     => 'green',
            self::CATEGORY_MAIN_COURSE => 'blue',
            self::CATEGORY_CHEESE      => 'yellow',
            self::CATEGORY_DESSERT     => 'brown'
        ];
    }

    /**
     * Get categories
     * @return array
     */
    public static function getCategoriesArray()
    {
        return [
            "Starter"     => self::CATEGORY_STARTER,
            "Main course" => self::CATEGORY_MAIN_COURSE,
            "Cheese"      => self::CATEGORY_CHEESE,
            "Dessert"     => self::CATEGORY_DESSERT
        ];
    }


    public function __toString()
    {
        return $this->getTitle() . ' (' . $this->getCategoryName() . ')';
    }
}
