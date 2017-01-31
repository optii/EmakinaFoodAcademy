<?php

namespace DamDan\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Customer
 *
 * @ORM\Table(name="customer")
 * @ORM\Entity(repositoryClass="DamDan\AppBundle\Repository\CustomerRepository")
 */
class Customer
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="reservationDay", type="datetime")
     */
    private $reservationDay;

    /**
     * @var int
     *
     * @ORM\Column(name="personsNumber", type="integer")
     */
    private $personsNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=200)
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=200)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=20)
     */
    private $phone;


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
     * Set reservationDay
     *
     * @param \DateTime $reservationDay
     *
     * @return Customer
     */
    public function setReservationDay($reservationDay)
    {
        $this->reservationDay = $reservationDay;

        return $this;
    }

    /**
     * Get reservationDay
     *
     * @return \DateTime
     */
    public function getReservationDay()
    {
        return $this->reservationDay;
    }

    /**
     * Set personsNumber
     *
     * @param integer $personsNumber
     *
     * @return Customer
     */
    public function setPersonsNumber($personsNumber)
    {
        $this->personsNumber = $personsNumber;

        return $this;
    }

    /**
     * Get personsNumber
     *
     * @return int
     */
    public function getPersonsNumber()
    {
        return $this->personsNumber;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Customer
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Customer
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Customer
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }
}

