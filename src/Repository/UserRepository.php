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
        // TODO: Implement loadUserByUsername() method.
//        return $this->createQueryBuilder('u')
//            ->where(' u.email = :email')
//            ->setParameter('email', $username)
//            ->getQuery()
//            ->getOneOrNullResult();
        $conn= $this->getEntityManager()->getConnection();

        //gdy bedziemy miec 2 role dla 1 uzytkownika to musisz zwrocic 2 row set z rolami odzielnie
        $sql = '
            select user.id, password, user.name as userName, surname, birth_date, pesel, document_id, email, telephone_number, role.name as roleName from user  
            join user_roles on user.id = user_roles.user_id join role  on role.id = user_roles.role_id
            where user.email = ?
        ';
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(1, $username);
            $rowCount = $stmt->execute();
            if($rowCount == 1) {
                $temp = $stmt->fetchAll();
//                return $temp[0];
                $user = new User();

                $user->setId($temp[0]['id']);
                $user->setPassword(($temp[0]['password']));
                $user->setName($temp[0]['userName']);
                $user->setSurname($temp[0]['surname']);
                $user->setBirthDate($temp[0]['birth_date']);
                $user->setPesel($temp[0]['pesel']);
                $user->setDocumentID($temp[0]['document_id']);
                $user->setEmail( $temp[0]['email']);
                $user->setTelephoneNumber($temp[0]['telephone_number']);

                $user->setRoles($temp[0]['roleName']);

                return $user;
            } else {
                return null;
            }
        } catch (DBALException $e) {
            return null;
        }

    }
}