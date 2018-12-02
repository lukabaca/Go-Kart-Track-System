<?php
namespace App\Repository\trackConfig;
use App\Entity\trackConfig\RideTimeDictionary;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityRepository;

class RideTimeDictionaryRepository extends EntityRepository
{
    public function getTimePerOneRide()
    {
        $sql = 'call getTimePerOneRide()';
        $conn = $this->getEntityManager()->getConnection();
        try {
            $stmt = $conn->prepare($sql);
            $rowCount = $stmt->execute();
            if ($rowCount == 1) {
               $timePerOneRideTemp = $stmt->fetch();
               $timePerOneRide = new RideTimeDictionary();
               $timePerOneRide->setId($timePerOneRideTemp['id']);
               $timePerOneRide->setRideCount($timePerOneRideTemp['ride_count']);
               $timePerOneRide->setTimePerRide($timePerOneRideTemp['time_per_ride']);
               return $timePerOneRide;
            }
            else {
                return null;
            }
        } catch (DBALException $e) {
            return null;
        }
    }
}