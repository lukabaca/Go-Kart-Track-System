<?php
/**
 * Created by PhpStorm.
 * User: Luka
 * Date: 2018-10-06
 * Time: 14:54
 */

namespace App\Controller;


use App\Entity\Lap;
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
        return $this->render('views/controllers/ride/index.html.twig', []);
    }

    /**
     * @Route("/laps/loadRecords/{limit}", name="laps/loadRecords/{limit}")
     */
    public function loadRecordsAction(Request $request, $limit)
    {
        if($limit > 100) {
            return new JsonResponse([], 400);
        }

        $recordsTemp = $this->getDoctrine()->getManager()->getRepository(Lap::class)->getRecords($limit);

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
        $test = $this->getDoctrine()->getManager()->getRepository(Lap::class)->getRecords(5);
        print_r($test);
        exit();
    }
}