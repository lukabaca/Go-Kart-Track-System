<?php
/**
 * Created by PhpStorm.
 * User: Luka
 * Date: 2018-10-09
 * Time: 20:01
 */

namespace App\Repository;


use App\Entity\Recording;
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityRepository;

class RecordingRepository extends EntityRepository
{
    public function findUserRecordings($userId) {
        $sql = 'call getUserRecordings(?)';
        $conn = $this->getEntityManager()->getConnection();
        try {
            $stmt = $conn->prepare($sql);
            $stmt->bindValue(1, $userId);
            $rowCount = $stmt->execute();
            if($rowCount > 0) {
                $recordings = [];
                $recordingsTemp = $stmt->fetchAll();

                foreach ($recordingsTemp as $recordingTemp) {
                    $id = $recordingTemp['id'];
                    $recordingLink = $recordingTemp['recording_link'];
                    $title = $recordingTemp['title'];

                    $recording = new Recording();
                    $recording->setId($id);
                    $recording->setRecordingLink($recordingLink);
                    $recording->setTitle($title);

                    $recordings [] = $recording;
                }

                return $recordings;
            } else {
                return [];
            }
        } catch (DBALException $e) {
            return null;
        }
    }

}