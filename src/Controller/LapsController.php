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
use App\Entity\LapSession;
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
     * @Route("/laps/userLapSessions", name="laps/userLapSessions")
     */
    public function userLapSessionsAction(Request $request)
    {
        return $this->render('views/controllers/laps/userLapSessions.html.twig', []
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
            $minute = $recordTemp->getMinute();
            $second = $recordTemp->getSecond();
            $milisecond = $recordTemp->getMilisecond();
            $time = $this->createProperTimeFormat($minute, $second, $milisecond);
            $record = [
                'id' => $recordTemp->getId(),
                'time' => $time,
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

    private function createProperTimeFormat($minute, $second, $milisecond) {
        if ($minute < 10) {
            $minute = '0' . $minute;
        }
        if ($second < 10) {
            $second = '0' . $second;
        }
        if ($milisecond < 10) {
            $milisecond = '0' . $milisecond;
        }
        $time = $minute . ':' . $second . ':' . $milisecond;
        return $time;
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
            $insertedLapsId = [];
            while (($line = fgetcsv($file)) !== FALSE) {
                if($i > 0) {
                    $lap = new Lap();
                    $time = $line[0];
                    $timeSplit = explode(':', $time);
                    $minute = $timeSplit[0];
                    $second = $timeSplit[1];
                    $milisecond = $timeSplit[2];
                    $averageSpeed = $line[1];
                    $dateString = $line[2];
                    $date = new DateTime($dateString);
                    $kartId = $line[3];
                    $userId = $line[4];
                    $lap->setMinute($minute);
                    $lap->setSecond($second);
                    $lap->setMilisecond($milisecond);
                    $lap->setAverageSpeed($averageSpeed);
                    $lap->setDate($date);
                    $kart = $this->getDoctrine()->getRepository(Kart::class)->find($kartId);
                    $user = $this->getDoctrine()->getRepository(User::class)->find($userId);
                    $lap->setKart($kart);
                    $lap->setUser($user);
                    $laps [] = $lap;
//                    $em = $this->getDoctrine()->getManager();
//                    $em->persist($lap);
//                    $em->flush();
//                    $insertedLapId = $lap->getId();
//                    $insertedLapsId [] = $insertedLapId;
                }
                $i = $i + 1;
            }
            $dates = [];
            foreach ($laps as $lap) {
                $dates [] = $lap->getDate();
            }
            $minDate = min($dates);
            $maxDate = max($dates);

            $lapSession = new LapSession();
            $lapSession->setStartDate($minDate);
            $lapSession->setEndDate($maxDate);
            foreach ($laps as $lap) {
                $lap->setLapSession($lapSession);
                $em = $this->getDoctrine()->getManager();
                $em->persist($lap);
                $em->flush();
                $insertedLapId = $lap->getId();
                $insertedLapsId [] = $insertedLapId;
            }
            fclose($file);
            return new JsonResponse($insertedLapsId, 200);
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