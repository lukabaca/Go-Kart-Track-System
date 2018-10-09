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
     * @ORM\Column(type="integer")
     */
    private $user_ID;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $recordingLink;

    /**
     * @ORM\Column(type="string", length=45)
     */
    private $title;
}