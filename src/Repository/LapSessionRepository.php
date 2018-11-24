<?php
/**
 * Created by PhpStorm.
 * User: Luka
 * Date: 2018-11-24
 * Time: 13:07
 */

namespace App\Repository;


use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityRepository;

class LapSessionRepository extends EntityRepository
{
    public function getLapSessions($start, $length, $columnName, $orderDir, $searchValue) {
        $sql = 'call getLapSessions(?, ?, ?, ?, ?)';
        $conn = $this->getEntityManager()->getConnection();
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $start);
            $stmt->bindValue(2, $length);
            $stmt->bindValue(3, $columnName);
            $stmt->bindValue(4, $orderDir);
            $stmt->bindValue(5, $searchValue);
            $rowCount = $stmt->execute();
            if($rowCount > 0) {
                $lapSessions = $stmt->fetchAll();
                return $lapSessions;
            } else {
                return [];
            }
        } catch (DBALException $e) {
            return [];
        }
    }
}