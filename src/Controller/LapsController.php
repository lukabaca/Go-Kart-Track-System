<?php
/**
 * Created by PhpStorm.
 * User: Luka
 * Date: 2018-10-06
 * Time: 14:54
 */

namespace App\Controller;


use App\Entity\Kart;
use App\Entity\Lap;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class LapsController extends Controller
{
    /**
     * @Route("/laps/index", name="laps/index")
     */
    public function indexAction(Request $request)
    {

        return $this->render('views/controllers/laps/index.html.twig', []
        );
    }

    /**
     * @Route("/laps/record/index", name="laps/record/index")
     */
    public function recordIndexAction(Request $request)
    {
        $defaultLimitRecord = 10;
        $limitRecordOptions = [10, 15, 20];
        return $this->render('views/controllers/laps/record.html.twig', [
            'limitRecordOptions' => $limitRecordOptions,
            'defaultLimitRecord' => $defaultLimitRecord
        ]);
    }

    /**
     * @Route("/laps/loadRecords/{limit}/{timeMode}", name="laps/loadRecords/{limit}/{timeMode}")
     */
    public function loadRecordsAction(Request $request, $limit, $timeMode)
    {
        if($limit > 100) {
            return new JsonResponse([], 400);
        }

        $recordsTemp = $this->getDoctrine()->getManager()->getRepository(Lap::class)->getRecords($limit, $timeMode);


        if(!$recordsTemp) {
            return new JsonResponse([], 404);
        }



        $records = [];
        foreach ($recordsTemp as $recordTemp) {
            $record = [
                'id' => $recordTemp->getId(),
                'user_id' => $recordTemp->getUser()->getId(),
                'kart_id' => $recordTemp->getKart()->getId(),
                'time' => $recordTemp->getTime(),
                'averageSpeed' => $recordTemp->getAverageSpeed(),
                'date' => $recordTemp->getDate()
            ];

            $records [] = $record;
        }


        return new JsonResponse($records, 200);
    }

    /**
     * @Route("/lapTest", name="lapTest")
     */
    public function test(Request $request)
    {
        $test = $this->getDoctrine()->getManager()->getRepository(Lap::class)->getRecords(5, 1);

        $userId = $test[0]->getUser()->getId();
        $kartId = $test[0]->getKart()->getId();

        $user = $this->getDoctrine()->getManager()->getRepository(User::class)->find($userId);
        $kart = $this->getDoctrine()->getManager()->getRepository(Kart::class)->find($userId);

        if(!$user || !$kart) {
            echo 'a';
        }

        print_r($user->getName());
        print_r($kart->getName());

        exit();
    }
}