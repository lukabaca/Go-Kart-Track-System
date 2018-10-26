<?php
/**
 * Created by PhpStorm.
 * User: Luka
 * Date: 2018-10-26
 * Time: 13:05
 */

namespace App\Repository;


use Doctrine\ORM\EntityRepository;

class ReservationRepository extends EntityRepository
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