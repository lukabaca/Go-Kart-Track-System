<?php
/**
 * Created by PhpStorm.
 * User: Luka
 * Date: 2018-10-26
 * Time: 13:03
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
/**
 *
 * @ORM\Table(name="reservation")
 * @ORM\Entity(repositoryClass="App\Repository\ReservationRepository")
 *
 */
class Reservation
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="string")
     */
    private $startDate;
    /**
     * @ORM\Column(type="string")
     */
    private $endDate;
    /**
     * @ORM\Column(type="decimal")
     */
    private $cost;
    /**
     * @ORM\Column(type="boolean")
     */
    private $byTimeReservationType;
    /**
     * @ORM\Column(type="text")
     */
    private $description;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="reservation")
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Kart")
     * @ORM\JoinTable(
     * name="reservation_kart",
     * joinColumns={@ORM\JoinColumn(name="reservation_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="kart_id", referencedColumnName="id")}
     * )
     */
    private $karts;

    /**
     * Reservation constructor.
     * @param $id
     * @param $startDate
     * @param $endDate
     * @param $cost
     * @param $user
     * @param $karts
     */
    public function __construct($startDate = null, $endDate = null, $cost = null, $byTimeReservationType = null, $description = null, $user = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->cost = $cost;
        $this->byTimeReservationType = $byTimeReservationType;
        $this->description = $description;
        $this->user = $user;
        $this->karts = new ArrayCollection();
    }


    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user): void
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * @param mixed $startDate
     */
    public function setStartDate($startDate): void
    {
        $this->startDate = $startDate;
    }

    /**
     * @return mixed
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * @param mixed $endDate
     */
    public function setEndDate($endDate): void
    {
        $this->endDate = $endDate;
    }

    /**
     * @return mixed
     */
    public function getCost()
    {
        return $this->cost;
    }

    /**
     * @param mixed $cost
     */
    public function setCost($cost): void
    {
        $this->cost = $cost;
    }

    /**
     * @return mixed
     */
    public function getKarts()
    {
        return $this->karts;
    }

    /**
     * @param mixed $karts
     */
    public function setKarts($karts): void
    {
        $this->karts = $karts;
    }
    /**
     * @return mixed
     */
    public function getByTimeReservationType()
    {
        return $this->byTimeReservationType;
    }

    /**
     * @param mixed $byTimeReservationType
     */
    public function setByTimeReservationType($byTimeReservationType): void
    {
        $this->byTimeReservationType = $byTimeReservationType;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description): void
    {
        $this->description = $description;
    }
}