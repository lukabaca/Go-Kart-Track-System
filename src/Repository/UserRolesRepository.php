<?php
/**
 * Created by PhpStorm.
 * User: Luka
 * Date: 2018-10-06
 * Time: 16:33
 */

namespace App\Repository;


use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityRepository;

class UserRolesRepository  extends EntityRepository
{
    public function insertUserAndRolesIDs($user_id, $role_id) {
        $sql = 'call insertUserAndRolesIds(?, ?)';
        $conn = $this->getEntityManager()->getConnection();
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $user_id);
            $stmt->bindValue(2, $role_id);
            $stmt->execute();
        } catch (DBALException $e) {
            return null;
        }
    }
}