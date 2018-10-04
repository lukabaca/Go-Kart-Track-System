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
class User
{
    private $email;
    private $password;
    private $name;
    private $surname;

    private $birthDate;
    private $pesel;
    private $documentID;
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