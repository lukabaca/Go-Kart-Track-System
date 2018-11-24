<?php
/**
 * Created by PhpStorm.
 * User: Luka
 * Date: 2018-10-15
 * Time: 13:36
 */

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Table(name="lap")
 * @ORM\Entity(repositoryClass="App\Repository\LapRepository")
 *
 */
class Lap
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="lap")
     */
    private $user;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Kart", inversedBy="lap")
     */
    private $kart;
    /**
     * @ORM\Column(type="integer")
     */
    private $minute;
    /**
     * @ORM\Column(type="integer")
     */
    private $second;
    /**
     * @ORM\Column(type="integer")
     */
    private $milisecond;
    /**
     * @ORM\Column(type="double")
     */
    private $averageSpeed;
    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * Lap constructor.
     * @param $id
     * @param $user
     * @param $kart
     * @param $minute
     * @param $second
     * @param $milisecond
     * @param $averageSpeed
     * @param $date
     */
    public function __construct($id = null, $user = null, $kart = null, $minute = null, $second = null, $milisecond = null, $averageSpeed = null, $date = null)
    {
        $this->id = $id;
        $this->user = $user;
        $this->kart = $kart;
        $this->minute = $minute;
        $this->second = $second;
        $this->milisecond = $milisecond;
        $this->averageSpeed = $averageSpeed;
        $this->date = $date;
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
    public function getKart()
    {
        return $this->kart;
    }

    /**
     * @param mixed $kart
     */
    public function setKart($kart): void
    {
        $this->kart = $kart;
    }

    /**
     * @return mixed
     */
    public function getMinute()
    {
        return $this->minute;
    }

    /**
     * @param mixed $minute
     */
    public function setMinute($minute): void
    {
        $this->minute = $minute;
    }

    /**
     * @return mixed
     */
    public function getSecond()
    {
        return $this->second;
    }

    /**
     * @param mixed $second
     */
    public function setSecond($second): void
    {
        $this->second = $second;
    }

    /**
     * @return mixed
     */
    public function getMilisecond()
    {
        return $this->milisecond;
    }

    /**
     * @param mixed $milisecond
     */
    public function setMilisecond($milisecond): void
    {
        $this->milisecond = $milisecond;
    }

    /**
     * @return mixed
     */
    public function getAverageSpeed()
    {
        return $this->averageSpeed;
    }

    /**
     * @param mixed $averageSpeed
     */
    public function setAverageSpeed($averageSpeed): void
    {
        $this->averageSpeed = $averageSpeed;
    }

    /**
     * @return mixed
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param mixed $date
     */
    public function setDate($date): void
    {
        $this->date = $date;
    }

}