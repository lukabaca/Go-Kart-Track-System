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
use App\Entity\LapSession;
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
                    $minute = $lapTemp['minute'];
                    $second = $lapTemp['second'];
                    $milisecond = $lapTemp['milisecond'];
                    $averageSpeed = $lapTemp['average_speed'];
                    $date = $lapTemp['date'];
                    $user_id = $lapTemp['user_id'];
                    $kart_id = $lapTemp['kart_id'];
                    $lap = new Lap();
                    $lap->setId($id);
                    $user = new User();
                    $user->setId($user_id);
                    $lap->setUser($user);
                    $lap->setKart(new Kart($kart_id));
                    $lap->setMinute($minute);
                    $lap->setSecond($second);
                    $lap->setMilisecond($milisecond);
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

    public function getLapsForSession($sessionId, $userId)
    {
        $sql = 'call getLapsForSession(?, ?)';
        $conn = $this->getEntityManager()->getConnection();
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $sessionId);
            $stmt->bindValue(2, $userId);
            $rowCount = $stmt->execute();
            if ($rowCount > 0) {
                $laps = [];
                $lapsTemp = $stmt->fetchAll();
                foreach ($lapsTemp as $lapTemp) {
                    $lap = new Lap();
                    $lapId = $lapTemp['id'];
                    $kartId = $lapTemp['kart_id'];
                    $lapSessiondId = $lapTemp['lap_session_id'];
                    $averageSpeed = $lapTemp['average_speed'];
                    $date = $lapTemp['date'];
                    $minute = $lapTemp['minute'];
                    $second = $lapTemp['second'];
                    $milisecond = $lapTemp['milisecond'];
                    $lap->setId($lapId);
                    $lap->setAverageSpeed($averageSpeed);
                    $lap->setDate($date);
                    $lap->setMinute($minute);
                    $lap->setSecond($second);
                    $lap->setMilisecond($milisecond);
                    $laps [] = [
                        'lap' => $lap,
                        'kartId' => $kartId,
                        'lapSessionId' => $lapSessiondId,
                    ];
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