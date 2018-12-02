<?php

namespace App\Entity\trackConfig;
use Doctrine\ORM\Mapping as ORM;
/**
 *
 * @ORM\Table(name="ride_time_dictionary")
 * @ORM\Entity(repositoryClass="App\Repository\trackConfig\RideTimeDictionaryRepository")
 *
 */
class RideTimeDictionary
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $ride_count;

    /**
     * @ORM\Column(type="time")
     */
    private $time_per_ride;

    /**
     * RideTimeDictionary constructor.
     * @param $id
     * @param $ride_count
     * @param $time_per_ride
     */
    public function __construct($id = null, $ride_count = null, $time_per_ride = null)
    {
        $this->id = $id;
        $this->ride_count = $ride_count;
        $this->time_per_ride = $time_per_ride;
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
    public function getRideCount()
    {
        return $this->ride_count;
    }

    /**
     * @param mixed $ride_count
     */
    public function setRideCount($ride_count): void
    {
        $this->ride_count = $ride_count;
    }

    /**
     * @return mixed
     */
    public function getTimePerRide()
    {
        return $this->time_per_ride;
    }

    /**
     * @param mixed $time_per_ride
     */
    public function setTimePerRide($time_per_ride): void
    {
        $this->time_per_ride = $time_per_ride;
    }
}