<?php
/**
 * Created by PhpStorm.
 * User: Luka
 * Date: 2018-10-09
 * Time: 20:01
 */

namespace App\Repository;


use Doctrine\ORM\EntityRepository;

class RecordingRepository extends EntityRepository
{
//    public function addRecording($recording) {
//        $em = $this->getEntityManager()->getConnection()->beginTransaction();
//        try {
//
//            $em->persist($recording);
//            $em->flush();
//            $em->getConnection()->commit();
//        } catch (Exception $e) {
//            $em->getConnection()->rollBack();
//            throw $e;
//        }
//    }
}