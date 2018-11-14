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
     * @ORM\Column(type="boolean")
     */
    private $availability;
    /**
     * @ORM\Column(type="float")
    *
     */
    private $prize;
    /**
     * @ORM\Column(type="string", length=45)
     * @Assert\Regex(
     *     pattern = "/^[a-zA-ZęóąśłżźćńĘÓĄŚŁŻŹĆŃ0-9 ]+$/",
     *     message="Wartość {{ value }} nie jest w poprawnym formacie"
     * )
     * @Assert\Length(
     *    max = 45,
     *    min = 2,
     *    maxMessage = "Maksymalna liczba znaków to 45",
     *    minMessage = "Minimalna liczba znaków to 2"
     * )
     */
    private $name;
    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $file;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Lap", mappedBy="kart")
     */
    private $lap;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\KartTechnicalData", mappedBy ="kart", cascade={"persist", "remove"})
     */
    private $kartTechnicalData;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Reservation")
     * @ORM\JoinTable(
     * name="reservation_kart",
     * joinColumns={@ORM\JoinColumn(name="kart_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="reservation_id", referencedColumnName="id")}
     * )
     */
    private $reservations;
    /**
     * Kart constructor.
     * @param $id
     * @param $availability
     * @param $prize
     * @param $name
     * @param $description
     * @param $lap
     */
    public function __construct($id = null, $availability = null, $prize = null, $name = null, $description = null, $lap = null, $file = null)
    {
        $this->id = $id;
        $this->availability = $availability;
        $this->prize = $prize;
        $this->name = $name;
        $this->description = $description;
        $this->lap = $lap;
        $this->file = $file;
        $this->reservations = new ArrayCollection();
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
        $kartTechnicalData->setKart($this);
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
     */
    public function setFile($file): void
    {
        $this->file = $file;
    }
}