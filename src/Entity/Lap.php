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
     * @ORM\Column(type="time")
     */
    private $time;
    /**
     * @ORM\Column(type="double")
     */
    private $averageSpeed;
    /**
     * @ORM\Column(type="date")
     */
    private $date;

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
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param mixed $time
     */
    public function setTime($time): void
    {
        $this->time = $time;
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