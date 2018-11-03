<?php
/**
 * Created by PhpStorm.
 * User: Luka
 * Date: 2018-10-15
 * Time: 13:41
 */

namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Table(name="kart")
 * @ORM\Entity(repositoryClass="App\Repository\KartRepository")
 *
 */
class Kart
{

    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=20)
     */
    private $availability;
    /**
     * @ORM\Column(type="float")
     */
    private $prize;
    /**
     * @ORM\Column(type="string", length=45)
     */
    private $name;
    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Lap", mappedBy="kart")
     */
    private $lap;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\KartTechnicalData", mappedBy ="kart")
     */
    private $kartTechnicalData;

    /**
     * Kart constructor.
     * @param $id
     * @param $availability
     * @param $prize
     * @param $name
     * @param $description
     * @param $lap
     */
    public function __construct($id = null, $availability = null, $prize = null, $name = null, $description = null, $lap = null)
    {
        $this->id = $id;
        $this->availability = $availability;
        $this->prize = $prize;
        $this->name = $name;
        $this->description = $description;
        $this->lap = $lap;
//        $this->kartTechnicalData = new ArrayCollection();
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
    public function getAvailability()
    {
        return $this->availability;
    }

    /**
     * @param mixed $availability
     */
    public function setAvailability($availability): void
    {
        $this->availability = $availability;
    }

    /**
     * @return mixed
     */
    public function getPrize()
    {
        return $this->prize;
    }

    /**
     * @param mixed $prize
     */
    public function setPrize($prize): void
    {
        $this->prize = $prize;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name): void
    {
        $this->name = $name;
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

    /**
     * @return mixed
     */
    public function getKartTechnicalData()
    {
        return $this->kartTechnicalData;
    }

    /**
     * @param mixed $kartTechnicalData
     */
    public function setKartTechnicalData($kartTechnicalData): void
    {
        $this->kartTechnicalData = $kartTechnicalData;
    }
}