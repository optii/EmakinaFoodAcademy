<?php

namespace DamDan\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Menu
 *
 * @ORM\Table(name="menu")
 * @ORM\Entity(repositoryClass="DamDan\AppBundle\Repository\MenuRepository")
 */
class Menu
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
     * @ORM\Column(name="title", type="string", length=100)
     */
    private $title;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var int
     *
     * @ORM\Column(name="appearanceOrder", type="integer")
     */
    private $appearanceOrder;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=50)
     */
    private $status;
    /* TODO
    /**
     * One author has many menus.
     * @ManyToOne(targetEntity="User", inversedBy="menus")
     * @JoinColumn(name="user_id", referencedColumnName="id")
     */
    //private $author;

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
     * Set appearanceOrder
     *
     * @param integer $appearanceOrder
     *
     * @return Menu
     */
    public function setAppearanceOrder($appearanceOrder)
    {
        $this->appearanceOrder = $appearanceOrder;

        return $this;
    }

    /**
     * Get appearanceOrder
     *
     * @return int
     */
    public function getAppearanceOrder()
    {
        return $this->appearanceOrder;
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
}

