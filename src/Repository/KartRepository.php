<?php
/**
 * Created by PhpStorm.
 * User: Luka
 * Date: 2018-10-15
 * Time: 13:42
 */

namespace App\Repository;

use App\Entity\Kart;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityRepository;
class KartRepository extends EntityRepository
{
    public function getKartsInReservation($reservation_id) {
        $sql = 'call getKartsInReservation(?)';
        $conn = $this->getEntityManager()->getConnection();
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $reservation_id);
            $rowCount = $stmt->execute();
            if($rowCount > 0) {
                $kartsTemp = $stmt->fetchAll();
                $karts = [];
                foreach ($kartsTemp as $kartTemp) {
                    $kart = new Kart();
                    $kart->setId($kartTemp['id']);
                    $kart->setName($kartTemp['name']);
                    $kart->setPrize($kartTemp['prize']);
                    $kart->setAvailability($kartTemp['availability']);
                    $karts [] = $kart;
                }
                return $karts;
            } else {
                return [];
            }
        } catch (DBALException $e) {
            return [];
        }
    }
    public function getKarts($start, $length, $columnName, $orderDir, $searchValue) {
        $sql = 'call getKarts(?, ?, ?, ?, ?)';
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
                $karts = $stmt->fetchAll();
                return $karts;
            } else {
                return [];
            }
        } catch (DBALException $e) {
            return [];
        }
    }

}