<?php
/**
 * Created by PhpStorm.
 * User: Luka
 * Date: 2018-10-15
 * Time: 13:38
 */

namespace App\Repository;

use App\Entity\Kart;
use App\Entity\Lap;
use App\Entity\User;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityRepository;
class LapRepository extends EntityRepository
{
    public function getRecords($recordLimit, $timeMode)
    {
        $sql = 'call getRecords(?, ?)';
        $conn = $this->getEntityManager()->getConnection();
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $recordLimit);
            $stmt->bindValue(2, $timeMode);
            $rowCount = $stmt->execute();
            if ($rowCount > 0) {
                $laps = [];
                $lapsTemp = $stmt->fetchAll();
                foreach ($lapsTemp as $lapTemp) {
                    $id = $lapTemp['id'];
                    $time = $lapTemp['time'];
                    $averageSpeed = $lapTemp['averageSpeed'];
                    $date = $lapTemp['date'];
                    $user_id = $lapTemp['user_id'];
                    $kart_id = $lapTemp['kart_id'];
                    $lap = new Lap();
                    $lap->setId($id);
                    $user = new User();
                    $user->setId($user_id);
                    $lap->setUser($user);
                    $lap->setKart(new Kart($kart_id));
                    $lap->setTime($time);
                    $lap->setAverageSpeed($averageSpeed);
                    $lap->setDate($date);
                    $laps [] = $lap;
                }
                return $laps;
            } else {
                return [];
            }
        } catch (DBALException $e) {
            return [];
        }
    }
}