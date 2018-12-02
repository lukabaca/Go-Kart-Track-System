<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
/**
 *
 * @ORM\Table(name="lap_session")
 * @ORM\Entity(repositoryClass="App\Repository\LapSessionRepository")
 *
 */
class LapSession
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $endDate;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Lap", mappedBy="lapSession")
     */
    private $lap;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="$lapSession")
     */
    private $user;

    /**
     * LapSession constructor.
     * @param $id
     * @param $startDate
     * @param $endDate
     * @param $lap
     */
    public function __construct($id = null, $startDate = null, $endDate = null, $lap = null)
    {
        $this->id = $id;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->lap = $lap;
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
    public function getLap()
    {
        return $this->lap;
    }

    /**
     * @param mixed $lap
     */
    public function setLap($lap): void
    {
        $this->lap = $lap;
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
}