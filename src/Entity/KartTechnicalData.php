<?php
/**
 * Created by PhpStorm.
 * User: Luka
 * Date: 2018-11-01
 * Time: 20:56
 */

namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 *
 * @ORM\Table(name="kart_technical_data")
 * @ORM\Entity(repositoryClass="App\Repository\KartTechnicalDataRepository")
 *
 */
class KartTechnicalData
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="integer")
     * @Assert\Regex(
     *     pattern = "/^[0-9]",
     *     message="Moc pojazdu musi być wyrażona jako liczba całkowita"
     * )
     */
    private $power;
    /**
     * @ORM\Column(type="float")
     */
    private $vmax;
    /**
     * @ORM\Column(type="string", length=45)
     */
    private $engine;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Kart", inversedBy="kartTechnicalData")
     */
    private $kart;


    /**
     * KartTechnicalData constructor.
     * @param $id
     * @param $power
     * @param $vmax
     * @param $engine
     */
    public function __construct($id = null, $power = null, $vmax = null, $engine = null)
    {
        $this->id = $id;
        $this->power = $power;
        $this->vmax = $vmax;
        $this->engine = $engine;
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
    public function getPower()
    {
        return $this->power;
    }

    /**
     * @param mixed $power
     */
    public function setPower($power): void
    {
        $this->power = $power;
    }

    /**
     * @return mixed
     */
    public function getVmax()
    {
        return $this->vmax;
    }

    /**
     * @param mixed $vmax
     */
    public function setVmax($vmax): void
    {
        $this->vmax = $vmax;
    }

    /**
     * @return mixed
     */
    public function getEngine()
    {
        return $this->engine;
    }

    /**
     * @param mixed $engine
     */
    public function setEngine($engine): void
    {
        $this->engine = $engine;
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
}