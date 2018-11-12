<?php
/**
 * Created by PhpStorm.
 * User: Luka
 * Date: 2018-11-10
 * Time: 21:40
 */

namespace App\Entity\trackConfig;

use Doctrine\ORM\Mapping as ORM;
/**
 *
 * @ORM\Table(name="trackInfo")
 * @ORM\Entity(repositoryClass="App\Repository\trackConfig\TrackInfoRepository")
 *
 */
class TrackInfo
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    /**
     * @ORM\Column(type="string", length=45)
     */
    private $street;
    /**
     * @ORM\Column(type="string", length=30)
     */
    private $city;
    /**
     * @ORM\Column(type="string", length=20)
     */
    private $telephone_number;
    /**
     * @ORM\Column(type="string", length=10)
     */
    private $hourStart;
    /**
     * @ORM\Column(type="string", length=10)
     */
    private $hourEnd;
    /**
     * @ORM\Column(type="string", length=60)
     */
    private $facebookLink;
    /**
     * @ORM\Column(type="string", length=60)
     */
    private $instagramLink;
    /**
     * @ORM\Column(type="string", length=45)
     */
    private $email;

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
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
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param mixed $street
     */
    public function setStreet($street): void
    {
        $this->street = $street;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city): void
    {
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getTelephoneNumber()
    {
        return $this->telephone_number;
    }

    /**
     * @param mixed $telephone_number
     */
    public function setTelephoneNumber($telephone_number): void
    {
        $this->telephone_number = $telephone_number;
    }

    /**
     * @return mixed
     */
    public function getHourStart()
    {
        return $this->hourStart;
    }

    /**
     * @param mixed $hourStart
     */
    public function setHourStart($hourStart): void
    {
        $this->hourStart = $hourStart;
    }

    /**
     * @return mixed
     */
    public function getHourEnd()
    {
        return $this->hourEnd;
    }

    /**
     * @param mixed $hourEnd
     */
    public function setHourEnd($hourEnd): void
    {
        $this->hourEnd = $hourEnd;
    }

    /**
     * @return mixed
     */
    public function getFacebookLink()
    {
        return $this->facebookLink;
    }

    /**
     * @param mixed $facebookLink
     */
    public function setFacebookLink($facebookLink): void
    {
        $this->facebookLink = $facebookLink;
    }

    /**
     * @return mixed
     */
    public function getInstagramLink()
    {
        return $this->instagramLink;
    }

    /**
     * @param mixed $instagramLink
     */
    public function setInstagramLink($instagramLink): void
    {
        $this->instagramLink = $instagramLink;
    }
}