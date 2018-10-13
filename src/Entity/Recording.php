<?php
/**
 * Created by PhpStorm.
 * User: Luka
 * Date: 2018-10-09
 * Time: 19:56
 */

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 *
 * @ORM\Table(name="recording")
 * @ORM\Entity(repositoryClass="App\Repository\RecordingRepository")
 *
 */
class Recording
{

    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="recording")
     */
    private $user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $recordingLink;

    /**
     *     @ORM\Column(type="string", length=45)
     *     @Assert\Regex(
     *     pattern = "/^[a-zA-ZęóąśłżźćńĘÓĄŚŁŻŹĆŃ]+$/",
     *     message="Wartość {{ value }} nie jest w poprawnym formacie"
     * )
     */
    private $title;

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

    /**
     * @return mixed
     */
    public function getRecordingLink()
    {
        return $this->recordingLink;
    }

    /**
     * @param mixed $recordingLink
     */
    public function setRecordingLink($recordingLink): void
    {
        $this->recordingLink = $recordingLink;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param mixed $title
     */
    public function setTitle($title): void
    {
        $this->title = $title;
    }
}