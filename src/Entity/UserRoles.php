<?php
/**
 * Created by PhpStorm.
 * User: Luka
 * Date: 2018-10-06
 * Time: 16:32
 */

namespace App\Entity;

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
use Doctrine\ORM\Mapping as ORM;
/**
 *
 * @ORM\Table(name="user_roles")
 * @ORM\Entity(repositoryClass="App\Repository\UserRolesRepository")
 *
 */
class UserRoles
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private $user_id;
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private $role_id;

    /**
     * UserRoles constructor.
     * @param $user_id
     * @param $role_id
     */
    public function __construct($user_id = null, $role_id = null)
    {
        $this->user_id = $user_id;
        $this->role_id = $role_id;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id): void
    {
        $this->user_id = $user_id;
    }

    /**
     * @return mixed
     */
    public function getRoleId()
    {
        return $this->role_id;
    }

    /**
     * @param mixed $role_id
     */
    public function setRoleId($role_id): void
    {
        $this->role_id = $role_id;
    }
}