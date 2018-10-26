<?php
/**
 * Created by PhpStorm.
 * User: Luka
 * Date: 2018-10-26
 * Time: 13:05
 */

namespace App\Repository;


use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityRepository;

class ReservationRepository extends EntityRepository
{
    public function isReservationValid($user_id, $startDate, $endDate, $cost) {
        $sql = 'call isReservationValid(?, ?, ?, ?)';
        $conn = $this->getEntityManager()->getConnection();
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $user_id);
            $stmt->bindValue(2, $startDate);
            $stmt->bindValue(3, $endDate);
            $stmt->bindValue(4, $cost);
            $rowCount = $stmt->execute();
            if($rowCount == 1) {
                $res = $stmt->fetch();
                if(!$res) {
                    return null;
                }
                for($i = 0; $i < 3; $i++) {
                   if(!empty($res[$i])) {
                       return $res[$i];
                   }
                }
            } else {
                return null;
            }
        } catch (DBALException $e) {
            return null;
        }
    }
}