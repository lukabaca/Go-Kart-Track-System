<?php
namespace App\Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;
/**
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity("email", message="Ten adres email jest już w użyciu")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Role")
     * @ORM\JoinTable(
     * name="user_roles",
     * joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id")},
     * inverseJoinColumns={@ORM\JoinColumn(name="role_id", referencedColumnName="id")}
     * )
     */
    private $roles;

    /**
     * @ORM\Column(type="string", length=30)
     *
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
     *
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
     * @Assert\Date(message="To nie jest poprawny format daty (wymagany YYYY-MM-DD)")
     */
    private $birthDate;

    /**
     * @ORM\Column(type="string", length=11)
     * @Assert\Regex(
     *     pattern = "/^[0-9]+$/",
     *     message="Pesel musi składać się wyłącznie z cyfr"
     * )
     * @Assert\Length(
     *    max = 11,
     *    min = 11,
     *    exactMessage="Numer pesel musi składać się z 11 cyfr",
     * )
     */
    private $pesel;

    /**
     * @ORM\Column(type="string", length=9)
     * @Assert\Regex(
     *     pattern = "/^[a-zA-Z]{3}[0-9]{6}/",
     *     message="Niepoprawny format serii i numeru dowodu osobistego (wymagane 3 litery oraz 6 cyfr)"
     * )
     * @Assert\Length(
     *    max = 9,
     *    min = 9,
     *    exactMessage="Wymagane są 3 litery oraz 6 cyfr",
     * )
     */
    private $documentID;


    /**
     * @ORM\Column(type="string", length=45,  unique=true)
     *
     * @Assert\Email(
     *     message = "'{{ value }}' nie jest adresem email",
     *     checkMX = true
     * )
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=15)
     *
     *
     * @Assert\Regex(
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

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Recording", mappedBy="user")
     */
    private $recording;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Lap", mappedBy="user")
     */
    private $lap;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reservation", mappedBy="user")
     */
    private $reservation;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\LapSession", mappedBy="user")
     */
    private $lapSession;

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
    public function getRecording()
    {
        return $this->recording;
    }

    /**
     * @param mixed $recording
     */
    public function setRecording($recording): void
    {
        $this->recording = $recording;
    }


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
        $this->roles = new ArrayCollection();
        $this->recording = new ArrayCollection();
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
    /**
     * @return mixed
     */
    public function getReservation()
    {
        return $this->reservation;
    }

    /**
     * @param mixed $reservation
     */
    public function setReservation($reservation): void
    {
        $this->reservation = $reservation;
    }


    /**
     * @return mixed
     */
    public function getLapSession()
    {
        return $this->lapSession;
    }

    /**
     * @param mixed $lapSession
     */
    public function setLapSession($lapSession): void
    {
        $this->lapSession = $lapSession;
    }
    /**
     * @param mixed $roles
     */
    public function setRoles($roles): void
    {
        $this->roles = $roles;
    }

    /**
     * Returns the roles granted to the user.
     *
     *     public function getRoles()
     *     {
     *         return array('ROLE_USER');
     *     }
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        // TODO: Implement getRoles() method.
//        return array('ROLE_USER');
        return $this->roles;
    }

    /**
     * Returns the salt that was originally used to encode the password.
     *
     * This can return null if the password was not encoded using a salt.
     *
     * @return string|null The salt
     */
    public function getSalt()
    {
        // TODO: Implement getSalt() method.
        return null;
    }

    /**
     * Returns the username used to authenticate the user.
     *
     * @return string The username
     */
    public function getUsername()
    {
       return $this->email;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
}