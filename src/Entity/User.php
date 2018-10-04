<?php
/**
 * Created by PhpStorm.
 * User: Luka
 * Date: 2018-10-04
 * Time: 15:38
 */

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
/**
 *
 * @Table(name="user")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 *
 */
class User
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue()
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=30)
     */
    /*
     * @Assert\Regex(
     *     pattern = "/^[a-zA-ZęóąśłżźćńĘÓĄŚŁŻŹĆŃ]+$/",
     *     message="Wartość {{ value }} nie jest w poprawnym formacie"
     * )
     * @Assert\Length(
     *    max = 30,
     *    min = 2,
     *    maxMessage = "Maksymalna liczba znaków to 30",
     *    minMessage = "Minimalna liczba znaków to 2"
     * )
     */
    private $name;
    /**
     * @ORM\Column(type="string", length=30)
     */
    /*
     * @Assert\Regex(
     *     pattern = "/^[a-zA-ZęóąśłżźćńĘÓĄŚŁŻŹĆŃ]+$/",
     *     message="Wartość {{ value }} nie jest w poprawnym formacie"
     * )
     * @Assert\Length(
     *    max = 30,
     *    min = 2,
     *    maxMessage = "Maksymalna liczba znaków to 30",
     *    minMessage = "Minimalna liczba znaków to 2"
     * )
     */
    private $surname;

    /**
     * @ORM\Column(type="date")
     */
    private $birthDate;

    /**
     * @ORM\Column(type="string", length=11)
     */
    /*
     * * @Assert\Regex(
     *     pattern = "/^[0-9]{11}/",
     *     message="Numer pesel musi składać się z 11 cyfr"
     * )
     */
    private $pesel;

    /**
     * @ORM\Column(type="string", length=9)
     */
    /*
     * * @Assert\Regex(
     *     pattern = "/^[a-zA-Z]{3}[0-9]{6}/",
     *     message="Niepoprawny format serii i numeru dowodu osobistego"
     * )
     */
    private $documentID;


    /**
     * @ORM\Column(type="string", length=45)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=15)
     */
    /*
     * * @Assert\Regex(
     *     pattern = "/^[0-9]{9}/",
     *     message="Numer telefonu musi składać się z 9 cyfr"
     * )
     */
    private $telephoneNumber;

    /**
     * User constructor.
     * @param $email
     * @param $password
     * @param $name
     * @param $surname
     * @param $birthDate
     * @param $pesel
     * @param $documentID
     * @param $telephoneNumber
     */
    public function __construct($email = null, $password = null, $name = null, $surname = null, $birthDate = null, $pesel = null, $documentID = null, $telephoneNumber = null)
    {
        $this->email = $email;
        $this->password = $password;
        $this->name = $name;
        $this->surname = $surname;
        $this->birthDate = $birthDate;
        $this->pesel = $pesel;
        $this->documentID = $documentID;
        $this->telephoneNumber = $telephoneNumber;
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
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
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
    public function getSurname()
    {
        return $this->surname;
    }

    /**
     * @param mixed $surname
     */
    public function setSurname($surname): void
    {
        $this->surname = $surname;
    }

    /**
     * @return mixed
     */
    public function getBirthDate()
    {
        return $this->birthDate;
    }

    /**
     * @param mixed $birthDate
     */
    public function setBirthDate($birthDate): void
    {
        $this->birthDate = $birthDate;
    }

    /**
     * @return mixed
     */
    public function getPesel()
    {
        return $this->pesel;
    }

    /**
     * @param mixed $pesel
     */
    public function setPesel($pesel): void
    {
        $this->pesel = $pesel;
    }

    /**
     * @return mixed
     */
    public function getDocumentID()
    {
        return $this->documentID;
    }

    /**
     * @param mixed $documentID
     */
    public function setDocumentID($documentID): void
    {
        $this->documentID = $documentID;
    }

    /**
     * @return mixed
     */
    public function getTelephoneNumber()
    {
        return $this->telephoneNumber;
    }

    /**
     * @param mixed $telephoneNumber
     */
    public function setTelephoneNumber($telephoneNumber): void
    {
        $this->telephoneNumber = $telephoneNumber;
    }
}