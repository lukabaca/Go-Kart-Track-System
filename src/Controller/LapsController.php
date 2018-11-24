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
use DateTime;
use Exception;
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
            return new JsonResponse(['max limit of laps to load is 100'], 400);
        }
        $recordsTemp = $this->getDoctrine()->getManager()->getRepository(Lap::class)->getRecords($limit, $timeMode);
        if(!$recordsTemp) {
            return new JsonResponse(['Records for this time has not been found'], 404);
        }
        $records = [];
        foreach ($recordsTemp as $recordTemp) {
            $userId = $recordTemp->getUser()->getId();
            $kartId = $recordTemp->getKart()->getId();
            $user = $this->getDoctrine()->getManager()->getRepository(User::class)->find($userId);
            $kart = $this->getDoctrine()->getManager()->getRepository(Kart::class)->find($kartId);
            if(!$user || !$kart) {
                return new JsonResponse([], 404);
            }
            $recordTemp->setUser($user);
            $recordTemp->setKart($kart);
            $record = [
                'id' => $recordTemp->getId(),
                'time' => $recordTemp->getTime(),
                'averageSpeed' => $recordTemp->getAverageSpeed(),
                'date' => $recordTemp->getDate(),
                'user' => [
                    'name' => $recordTemp->getUser()->getName(),
                    'surname' => $recordTemp->getUser()->getSurname()
                ],
                'kart' => [
                    'name' => $recordTemp->getKart()->getName(),
                ]
            ];
            $records [] = $record;
        }
        return new JsonResponse($records, 200);
    }

    /**
     * @Route("/laps/readingCSV", name="/laps/readingCSV")
     */
    public function readingCSVAction(Request $request)
    {
        try {
            $file = fopen(__DIR__ . '/' . "test.csv", "r");
            $laps = [];
            $i = 0;
            while (($line = fgetcsv($file)) !== FALSE) {
                if($i > 0) {
                    $lap = new Lap();
                    $time = $line[0];
                    $timeSplit = explode(':', $time);
                    $minute = $timeSplit[0];
                    $second = $timeSplit[1];
                    $milisecond = $timeSplit[2];
                    print_r($minute);
                    print_r($second);
                    print_r($milisecond);
                    exit();
                    $lap->setTime($timeDate);
                    $lap->setAverageSpeed($line[1]);
                    $lap->setDate($line[2]);
                    $kart = $this->getDoctrine()->getRepository(Kart::class)->find($line[3]);
                    $user = $this->getDoctrine()->getRepository(User::class)->find($line[4]);
                    $lap->setKart($kart);
                    $lap->setUser($user);
                    $em = $this->getDoctrine()->getManager();
                    $em->persist($lap);
                    $em->flush();
                    print_r($lap->getId());
                }
                $i = $i + 1;
//                $laps [] = $lap;
            }
            fclose($file);
            exit();
            return new JsonResponse($laps, 200);
        } catch (Exception $e) {
            print_r($e->getMessage());
            exit();
            return new JsonResponse('cant locate file', 404);
        }
    }

    /**
     * @Route("/lapTest", name="lapTest")
     */
    public function test(Request $request)
    {
        try {
            $file = fopen(__DIR__ . '/' . "test.csv", "r");
            $laps = [];
            while (($line = fgetcsv($file)) !== FALSE) {
                $lap = new Lap();
                $lap->setTime($line[0]);
                $lap->setAverageSpeed($line[1]);
                $lap->setDate($line[2]);
                $kart = $this->getDoctrine()->getRepository(Kart::class)->find($line[3]);
                if (!$kart) {
                    $kartName = '';
                } else {
                    $lap->setKart($kart);
                    $kartName = $lap->getKart()->getName();
                }
                $user = $this->getDoctrine()->getRepository(User::class)->find($line[4]);
                if(!$user) {
                    $userName = '';
                    $userSurname = '';
                } else {
                    $lap->setUser($user);
                    $userName = $lap->getUser()->getName();
                    $userSurname = $lap->getUser()->getSurname();
                }

                $lap = [
                    'time' => $lap->getTime(),
                    'averageSpeed' => $lap->getAverageSpeed(),
                    'date' => $lap->getDate(),
                    'user' => [
                        'name' => $userName,
                        'surname' => $userSurname,
                    ],
                    'kart' => [
                        'name' => $kartName,
                    ]
                ];
                $laps [] = $lap;
            }
            fclose($file);
            return new JsonResponse($laps, 200);
        } catch (Exception $e) {
            return new JsonResponse('cant locate file', 404);
        }
    }

}