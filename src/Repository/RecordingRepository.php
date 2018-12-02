<?php
namespace App\Repository;
use App\Entity\Recording;
use App\Entity\User;
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
                    $userId = $recordingTemp['user_id'];
                    $recording = new Recording();
                    $recording->setId($id);
                    $recording->setRecordingLink($recordingLink);
                    $recording->setTitle($title);
                    $recording->setUser(new User($userId));
                    $recordings [] = $recording;
                }
                return $recordings;
            } else {
                return [];
            }
        } catch (DBALException $e) {
            return [];
        }
    }

}