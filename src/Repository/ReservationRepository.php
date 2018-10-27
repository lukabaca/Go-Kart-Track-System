<?php
/**
 * Created by PhpStorm.
 * User: Luka
 * Date: 2018-10-26
 * Time: 13:05
 */

namespace App\Repository;


use App\Entity\Reservation;
use App\Entity\ReservationKart;
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
    public function makeReservation($user_id, $startDate, $endDate, $cost) {
        $sql = 'call makeReservation(?, ?, ?, ?)';
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
                $reservation = new Reservation();
                $reservation->setId($res['id']);
                $reservation->setUser($res['user_id']);
                $reservation->setStartDate($res['start_date']);
                $reservation->setEndDate($res['end_date']);
                $reservation->setCost($res['cost']);
                return $reservation;
            } else {
                return null;
            }
        } catch (DBALException $e) {
            return null;
        }
    }
    public function insertReservationAndKartIds($reservation_id, $kart_id) {
        $sql = 'call insertReservationAndKartIds(?, ?)';
        $conn = $this->getEntityManager()->getConnection();
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $reservation_id);
            $stmt->bindValue(2, $kart_id);
            $rowCount = $stmt->execute();
            if($rowCount == 1) {
                $res = $stmt->fetch();
                if(!$res) {
                    return null;
                }
                $reservation = new ReservationKart();
                $reservation->setReservationId($res['reservation_id']);
                $reservation->getKartId($res['kart_id']);
                return $reservation;
            } else {
                return null;
            }
        } catch (DBALException $e) {
            return null;
        }
    }
}