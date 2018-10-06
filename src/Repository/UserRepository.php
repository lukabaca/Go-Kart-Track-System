<?php
/**
 * Created by PhpStorm.
 * User: Luka
 * Date: 2018-10-04
 * Time: 22:43
 */

namespace App\Repository;

use App\Entity\User;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserRepository extends EntityRepository implements UserLoaderInterface
{

    /**
     * Loads the user for the given username.
     *
     * This method must return null if the user is not found.
     *
     * @param string $username The username
     *
     * @return UserInterface|null
     */
    public function loadUserByUsername($username)
    {
        $sql1 = 'call getUserByUserEmail(?)';
        $sql2 = 'call getUserRolesByUserID(?)';
        $conn = $this->getEntityManager()->getConnection();
        try {
            $stmt = $conn->prepare($sql1);
            $stmt->bindValue(1, $username);
            $rowCount = $stmt->execute();
            if($rowCount == 1) {
                $tempUser = $stmt->fetchAll();
                $userID = $tempUser[0]['id'];

                $stmt = $conn->prepare($sql2);
                $stmt->bindValue(1, $userID);
                $rowCount = $stmt->execute();
                if($rowCount > 0) {
                    $tempRoles = $stmt->fetchAll();

                    $user = new User();

                    $user->setId($tempUser[0]['id']);
                    $user->setPassword(($tempUser[0]['password']));
                    $user->setName($tempUser[0]['name']);
                    $user->setSurname($tempUser[0]['surname']);
                    $user->setBirthDate($tempUser[0]['birth_date']);
                    $user->setPesel($tempUser[0]['pesel']);
                    $user->setDocumentID($tempUser[0]['document_id']);
                    $user->setEmail( $tempUser[0]['email']);
                    $user->setTelephoneNumber($tempUser[0]['telephone_number']);

                    $roles = [];
                    foreach($tempRoles as $role) {
                        $roles [] = $role['name'];
                    }
                    $user->setRoles($roles);

                    return $user;
                } else {
                    return null;
                }
            }

        } catch (DBALException $e) {
            return null;
        }
    }
}