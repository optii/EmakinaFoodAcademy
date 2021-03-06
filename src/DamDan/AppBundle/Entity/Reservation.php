<?php

namespace DamDan\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Reservation
 *
 * @ORM\Table(name="reservation")
 * @ORM\Entity(repositoryClass="DamDan\AppBundle\Repository\ReservationRepository")
 */
class Reservation
{
    /**
     * Status constants
     */
    const STATUS_PENDING       = 0;
    const STATUS_ACCEPTED      = 1;
    const STATUS_REFUSED       = 2;

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
     * @Assert\NotBlank()
     * @ORM\Column(name="date", type="datetime")
     */
    private $date;

    /**
     * @var int
     *
     * @Assert\NotBlank()
     * @Assert\GreaterThanOrEqual(0)
     * @ORM\Column(name="seats", type="integer")
     */
    private $seats;

    /**
     * @var string
     *
     * @Assert\Email(
     *     message = "The email is not valid.",
     *     checkMX = true
     * )
     * @Assert\NotBlank()
     * @ORM\Column(name="email", type="string", length=200)
     */
    private $email;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="name", type="string", length=200)
     */
    private $name;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @ORM\Column(name="phone", type="string", length=20)
     */
    private $phone;

    /**
     * @var int
     *
     * @ORM\Column(name="accepted", type="smallint")
     */
     private $accepted;

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
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     * @return $this
     */
    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @return int
     */
    public function getSeats()
    {
        return $this->seats;
    }

    /**
     * @param int $seats
     * @return $this
     */
    public function setSeats($seats)
    {
        $this->seats = $seats;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return $this
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    public function setAccepted($status)
    {
        $this->accepted = $status;
    }

    /**
     * @return int
     */
    public function getAccepted()
    {
        return $this->accepted;
    }

    public function isAccepted()
    {
        return $this->accepted == 1;
    }

    public function isRefused()
    {
        return $this->accepted == 2;
    }

    public function __toString()
    {
        return $this->getName(). ' - '. $this->getSeats();
    }
}
