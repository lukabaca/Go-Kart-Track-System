<?php
namespace App\Repository;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityRepository;

class LapSessionRepository extends EntityRepository
{
    public function getLapSessions($start, $length, $columnName, $orderDir, $searchValue, $userId) {
        $sql = 'call getLapSessions(?, ?, ?, ?, ?, ?)';
        $conn = $this->getEntityManager()->getConnection();
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $start);
            $stmt->bindValue(2, $length);
            $stmt->bindValue(3, $columnName);
            $stmt->bindValue(4, $orderDir);
            $stmt->bindValue(5, $searchValue);
            $stmt->bindValue(6, $userId);
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