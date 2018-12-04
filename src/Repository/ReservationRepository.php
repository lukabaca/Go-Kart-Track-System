<?php
namespace App\Repository;
use App\Entity\Reservation;
use App\Entity\ReservationKart;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityRepository;

class ReservationRepository extends EntityRepository
{
    public function isReservationValid($user_id, $startDate, $endDate, $byTimeReservationType) {
        $sql = 'call isReservationValid(?, ?, ?, ?)';
        $conn = $this->getEntityManager()->getConnection();
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $user_id);
            $stmt->bindValue(2, $startDate);
            $stmt->bindValue(3, $endDate);
            $stmt->bindValue(4, $byTimeReservationType);
            $rowCount = $stmt->execute();
            if($rowCount == 1) {
                $res = $stmt->fetch();
                if(!$res) {
                    return null;
                }
                for($i = 0; $i < 4; $i++) {
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

    public function getKartPrizeByNumberOfRides($kart_id, $numberOfRides) {
        $sql = 'call getKartPrizeByNumberOfRides(?, ?)';
        $conn = $this->getEntityManager()->getConnection();
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $kart_id);
            $stmt->bindValue(2, $numberOfRides);
            $rowCount = $stmt->execute();
            if($rowCount == 1) {
                $res = $stmt->fetch();
                if(!$res) {
                    return null;
                }
                $prize = $res['totalKartPrize'];
                return $prize;
            } else {
                return null;
            }
        } catch (DBALException $e) {
            return null;
        }
    }

    public function getReservationsForViewType($date, $viewType) {
        $sql = 'call getReservationsForViewType(?, ?)';
        $conn = $this->getEntityManager()->getConnection();
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $date);
            $stmt->bindValue(2, $viewType);
            $rowCount = $stmt->execute();
            if($rowCount > 0) {
                $reservationsTemp = $stmt->fetchAll();
                $reservations = [];
                foreach ($reservationsTemp as $reservationTemp) {
                    $reservation = new Reservation();
                    $reservation->setId($reservationTemp['id']);
                    $reservation->setStartDate($reservationTemp['start_date']);
                    $reservation->setEndDate($reservationTemp['end_date']);
                    $reservation->setByTimeReservationType($reservationTemp['by_time_reservation_type']);
                    $reservations [] = $reservation;
                }
                return $reservations;
            } else {
                return [];
            }
        } catch (DBALException $e) {
            return [];
        }
    }

    public function getUserReservations($user_id) {
        $sql = 'call getUserReservations(?)';
        $conn = $this->getEntityManager()->getConnection();
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $user_id);
            $rowCount = $stmt->execute();
            if($rowCount > 0) {
                $reservationsTemp = $stmt->fetchAll();
                $reservations = [];
                foreach ($reservationsTemp as $reservationTemp) {
                    $reservation = new Reservation();
                    $reservation->setId($reservationTemp['id']);
                    $reservation->setStartDate($reservationTemp['start_date']);
                    $reservation->setEndDate($reservationTemp['end_date']);
                    $reservation->setCost($reservationTemp['cost']);
                    $reservations [] = $reservation;
                }
                return $reservations;
            } else {
                return [];
            }
        } catch (DBALException $e) {
            return [];
        }
    }

    public function getTimeTypeReservations() {
        $sql = 'call getTimeTypeReservations()';
        $conn = $this->getEntityManager()->getConnection();
        try {
            $stmt = $conn->prepare($sql);
            $rowCount = $stmt->execute();
            if($rowCount > 0) {
                $reservationsTemp = $stmt->fetchAll();
                $reservations = [];
                foreach ($reservationsTemp as $reservationTemp) {
                    $reservation = new Reservation();
                    $reservation->setId($reservationTemp['id']);
                    $reservation->setStartDate($reservationTemp['start_date']);
                    $reservation->setEndDate($reservationTemp['end_date']);
                    $reservation->setCost($reservationTemp['cost']);
                    $reservation->setDescription($reservationTemp['description']);
                    $reservations [] = $reservation;
                }
                return $reservations;
            } else {
                return [];
            }
        } catch (DBALException $e) {
            return [];
        }
    }

    public function getReservations($start, $length, $columnName, $orderDir, $searchValue) {
        $sql = 'call getReservations(?, ?, ?, ?, ?)';
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