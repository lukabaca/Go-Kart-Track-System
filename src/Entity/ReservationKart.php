<?php
/**
 * Created by PhpStorm.
 * User: Luka
 * Date: 2018-10-26
 * Time: 19:09
 */

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 *
 * @ORM\Table(name="reservation_kart")
 * @ORM\Entity(repositoryClass="App\Repository\ReservationKartRepository")
 *
 */
class ReservationKart
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private $reservation_id;
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private $kart_id;

    /**
     * ReservationKart constructor.
     * @param $reservation_id
     * @param $kart_id
     */
    public function __construct($reservation_id = null, $kart_id = null)
    {
        $this->reservation_id = $reservation_id;
        $this->kart_id = $kart_id;
    }

    /**
     * @return mixed
     */
    public function getReservationId()
    {
        return $this->reservation_id;
    }

    /**
     * @param mixed $reservation_id
     */
    public function setReservationId($reservation_id): void
    {
        $this->reservation_id = $reservation_id;
    }

    /**
     * @return mixed
     */
    public function getKartId()
    {
        return $this->kart_id;
    }

    /**
     * @param mixed $kart_id
     */
    public function setKartId($kart_id): void
    {
        $this->kart_id = $kart_id;
    }

}