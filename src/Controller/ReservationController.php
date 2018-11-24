<?php
/**
 * Created by PhpStorm.
 * User: Luka
 * Date: 2018-10-20
 * Time: 12:59
 */

namespace App\Controller;


use App\Entity\Kart;
use App\Entity\Reservation;
use App\Entity\trackConfig\RideTimeDictionary;
use App\Entity\trackConfig\TrackInfo;
use App\Repository\trackConfig\RideTimeDictionaryRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


class ReservationController extends Controller
{
    /**
     * @Route("/reservation/index", name="reservation/index")
     */
    public function indexAction(Request $request)
    {
        return $this->render('views/controllers/reservation/index.html.twig', [
            ]
        );
    }
    /**
     * @Route("/reservation/timeTypeReservation", name="reservation/timeTypeReservation")
     */
    public function timeTypeReservationAction(Request $request)
    {
        return $this->render('views/controllers/reservation/timeTypeReservation.html.twig', [
            ]
        );
    }

    /**
     * @Route("/reservation/userReservation", name="reservation/userReservation")
     */
    public function userReservationAction(Request $request)
    {
        $reservationsTemp = $this->getDoctrine()->getManager()->getRepository(Reservation::class)->getUserReservations($this->getUser()->getId());
        $reservations = [];
        foreach ($reservationsTemp as $reservationTemp) {
            $startDateTemp = new DateTime($reservationTemp->getStartDate());
            $startDate = $startDateTemp->format('Y-m-d');
            $startDateHourAndMinutes = $startDateTemp->format('H:i:s');
            $endDateTemp = new DateTime($reservationTemp->getEndDate());
            $endDate = $endDateTemp->format('Y-m-d');
            $endDateHourAndMinutes = $endDateTemp->format('H:i:s');
            $reservation = [
                'id' => $reservationTemp->getId(),
                'startDate' => $startDate,
                'startDateHourAndMinutes' => $startDateHourAndMinutes,
                'endDate' => $endDate,
                'endDateHourAndMinutes' => $endDateHourAndMinutes,
                'cost' => $reservationTemp->getCost(),
            ];
            $reservations [] = $reservation;
        }
        return $this->render('views/controllers/reservation/userReservation.html.twig', [
                'reservations' => $reservations
            ]
        );
    }
    /**
     * @Route("/reservation/datatable", name="reservation/datatable")
     * @IsGranted("ROLE_ADMIN")
     */
    public function datatableAction(Request $request)
    {
        if ($request->getMethod() == 'POST') {
            $draw = intval($request->request->get('draw'));
            $start = $request->request->get('start');
            $length = $request->request->get('length');
            $search = $request->request->get('search');
            $orders = $request->request->get('order');
            $columns = $request->request->get('columns');
            $orderColumn = $orders[0]['column'];
            $orderDir = $orders[0]['dir'];
            $searchValue = $search['value'];
            foreach ($columns as $key => $column)
            {
                if ($orderColumn == $key) {
                    $orderColumnName = $column['name'];
                }
            }
            $res = $this->getDoctrine()->getRepository(Reservation::class)->
            getReservations($start, $length, $orderColumnName, $orderDir, $searchValue);
            $recordsTotalCount = count($this->getDoctrine()->getRepository(Reservation::class)->findAll());
            $response = [
                "draw" => $draw,
                "recordsTotal" => $recordsTotalCount,
                "recordsFiltered" => $recordsTotalCount,
                "data" => $res,
            ];
            return new JsonResponse($response, 200);
        } else {
            return new JsonResponse([], 400);
        }
    }

    /**
     * @Route("/reservation/manageReservations", name="/reservation/manageReservations")
     */
    public function manageVehiclesAction(Request $request) {
        return $this->render('views/controllers/reservation/manageReservations.html.twig' ,[
        ]);
    }


    /**
     * @Route("/reservation/getTimePerOneRide", name="reservation/getTimePerOneRide")
     */
    public function getTimePerOneRideAction(Request $request)
    {
        $timePerOneRideTemp = $this->getDoctrine()->getManager()->getRepository(RideTimeDictionary::class)->getTimePerOneRide();
        if(!$timePerOneRideTemp) {
            return new JsonResponse([], 404);
        }
        $timePerOneRide = [
            'id' => $timePerOneRideTemp->getId(),
            'rideCount' => $timePerOneRideTemp->getRideCount(),
            'timePerRide' => $timePerOneRideTemp->getTimePerRide()
        ];
        return new JsonResponse($timePerOneRide, 200);
    }
    /**
     * @Route("/reservation/getTrackConfig", name="reservation/getTrackConfig")
     */
    public function getTrackConfigeAction(Request $request)
    {
        $trackInfo = $this->getDoctrine()->getRepository(TrackInfo::class)->find(1);
        if(!$trackInfo) {
            return new JsonResponse(['no track config data'], 404);
        }
//        $hourStart = $trackInfo->getHourStart() ? $trackInfo->getHourStart() : null;
//        $hourEnd = $trackInfo->getHourEnd() ? $trackInfo->getHourEnd() : null;
//        $hourStart = $trackInfo->getHourStart()->format('H:i');
//        $hourEnd = $trackInfo->getHourEnd()->format('H:i');
        $hourStart = $trackInfo->getHourStart();
        $hourEnd = $trackInfo->getHourEnd();
        $trackInfo = [
            'hourStart' => $hourStart,
            'hourEnd' => $hourEnd,
        ];
        return new JsonResponse($trackInfo, 200);
    }
    /**
     * @Route("/reservation/deleteReservation/{id}", name="/reservation/deleteReservation/{id}")
     */
    public function deleteReservationAction(Request $request, $id)
    {
        $reservation = $this->getDoctrine()->getManager()->getRepository(Reservation::class)->find($id);
        if(!$reservation) {
            return new JsonResponse([], 404);
        }
        $user_id = $reservation->getUser()->getId();
        if($user_id != $this->getUser()->getId()) {
            return new JsonResponse(['cant delete someones reservation'], 403);
        }
        $em = $this->getDoctrine()->getManager();
        $em->remove($reservation);
        $em->flush();
        return new JsonResponse([], 200);
    }

    /**
     * @Route("/reservation/getTotalKartPrizeForReservation", name="/reservation/getTotalKartPrizeForReservation")
     */
    public function getTotalKartPrizeForReservationAction(Request $request)
    {
        if ($request->request->get('kartData')) {
            $kartData = json_decode($request->request->get('kartData'));
            $numberOfRides = $kartData->{'numberOfRides'};
            $kartIds = $kartData->{'karts'};
            $totalPrize = 0;
            foreach ($kartIds as $kartId) {
                $prize = $this->getDoctrine()->getManager()->getRepository(Reservation::class)->getKartPrizeByNumberOfRides($kartId, $numberOfRides);
                if (!$prize) {
                    return new JsonResponse([], 404);
                }
                if ($prize == -1) {
                    return new JsonResponse(['Bad input value'], 400);
                }
                $totalPrize = $totalPrize + $prize;
            }
            return new JsonResponse($totalPrize, 200);
        } else {
            return new JsonResponse([], 500);
        }
    }

    /**
     * @Route("/reservation/makeReservation", name="/reservation/makeReservation")
     */
    public function makeReservationAction(Request $request)
    {
        if ($request->request->get('reservationData')) {
            $reservationData = json_decode($request->request->get('reservationData'));

            $startDate = new DateTime($reservationData->{'startDate'});
            $startDate = $startDate->format('Y-m-d H:i:s');
            $endDate = new DateTime($reservationData->{'endDate'});
            $endDate = $endDate->format('Y-m-d H:i:s');
            $cost = $reservationData->{'cost'};
            $byTimeReservationType = $reservationData->{'byTimeReservationType'};
            $description = $reservationData->{'description'};
            $kartIds = $reservationData->{'karts'};

            $isReservationValid = $this->getDoctrine()->getManager()->getRepository(Reservation::class)->isReservationValid($this->getUser()->getId(),
                $startDate, $endDate, $cost);
            if($isReservationValid == 0) {
                return new JsonResponse(['Other reservation in this hour exists'], 409);
            }
            if($isReservationValid == 2) {
                return new JsonResponse(['Wrong dates (too early or too late)'], 400);
            }
            $reservation = new Reservation($startDate, $endDate, $cost, $byTimeReservationType, $description, $this->getUser());
            if(!$byTimeReservationType) {
                $karts = new ArrayCollection();
                foreach ($kartIds as $kartId) {
                    $kart = $this->getDoctrine()->getRepository(Kart::class)->find($kartId);
                    if(!$kart) {
                        return new JsonResponse('couldnt find send karts', 500);
                    }
                    $karts [] = $kart;
                }
                $reservation->setKarts($karts);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($reservation);
            $em->flush();
            $reservation = [
                'id' => $reservation->getId(),
                'user_id' => $reservation->getUser()->getId(),
                'startDate' => $reservation->getStartDate(),
                'endDate' => $reservation->getEndDate(),
                'cost' => $reservation->getCost(),
                'by_time_reservation_type' => $reservation->getByTimeReservationType(),
                'description' => $reservation->getDescription(),
            ];
            return new JsonResponse($reservation, 201);
        } else {
            return new JsonResponse(['nie otrzymano danych'], 500);
        }
    }

    /**
     * @Route("/reservation/getKarts", name="reservation/getKarts")
     */
    public function getKartsAction(Request $request)
    {
       $karts = $this->getDoctrine()->getManager()->getRepository(Kart::class)->findAll();
       if(!$karts) {
           return new JsonResponse([], 404);
       }
       $kartsRes = [];
       foreach ($karts as $kart) {
            $kartTemp = [
                'id' => $kart->getId(),
                'name' => $kart->getName(),
                'prize' => $kart->getPrize(),
                'availability' => $kart->getAvailability(),
            ];
            $kartsRes [] = $kartTemp;
       }

       return new JsonResponse($kartsRes, 200);
    }

    /**
     * @Route("/reservation/getKart/{id}", name="reservation/getKart/{id}")
     */
    public function getKartAction(Request $request, $id)
    {
        $kart = $this->getDoctrine()->getManager()->getRepository(Kart::class)->find($id);
        if(!$kart) {
            return new JsonResponse([], 404);
        }
        $kartRes = [
            'id' => $kart->getId(),
            'name' => $kart->getName(),
            'prize' => $kart->getPrize(),
        ];
        return new JsonResponse($kartRes, 200);
    }

    /**
     * @Route("/reservation/reservationDetails/{id}", name="reservation/reservationDetails/{id}")
     */
    public function reservationDetailsAction(Request $request, $id)
    {
        $reservation = $this->getDoctrine()->getManager()->getRepository(Reservation::class)->find($id);
        if(!$reservation) {
            return $this->render('views/alerts/404.html.twig', []);
        }
        $userIdForReservation = $reservation->getUser()->getId();
        if($userIdForReservation != $this->getUser()->getId()) {
            throw $this->createAccessDeniedException('You cannot access this page!');
        }
        $kartsInReservation = new ArrayCollection();
        $kartsInReservation = $reservation->getKarts();
        $startDate = date_create($reservation->getStartDate());
        $startDateHour = date_format($startDate, 'H:i');
        $endDate = date_create($reservation->getEndDate());
        $endDateHour = date_format($endDate, 'H:i');
        $date = date_format($startDate, 'd-m-Y');
        return $this->render('views/controllers/reservation/reservationDetails.html.twig', [
            'date' => $date,
            'startDateHour' => $startDateHour,
            'endDateHour' => $endDateHour,
            'reservation' => $reservation,
            'karts' => $kartsInReservation
        ]
        );
    }

    /**
     * @Route("/reservation/calendar", name="reservation/calendar")
     */
    public function calendarAction(Request $request)
    {
        return $this->render('views/controllers/reservation/calendar.html.twig', []
        );
    }

    /**
     * @Route("/reservation/getReservations/{date}/{viewType}", name="reservation/getKart/{date}/{viewType}")
     */
    public function getReservationsAction(Request $request, $date, $viewType)
    {
        $reservations = $this->getDoctrine()->getManager()->getRepository(Reservation::class)->getReservationsForViewType($date, $viewType);
        $reservationRes = [];
        foreach ($reservations as $reservation) {
            $dateStart = new DateTime($reservation->getStartDate());
            $dateEnd = new DateTime($reservation->getEndDate());

            $yearStart = date_format($dateStart, 'Y');
            $monthStart = date_format($dateStart, 'm');
            $dayStart = date_format($dateStart, 'd');
            $hourStart = date_format($dateStart, 'H');
            $minuteStart = date_format($dateStart, 'i');

            $yearEnd = date_format($dateEnd, 'Y');
            $monthEnd = date_format($dateEnd, 'm');
            $dayEnd = date_format($dateEnd, 'd');
            $hourEnd = date_format($dateEnd, 'H');
            $minuteEnd = date_format($dateEnd, 'i');

            $reservationTemp = [
                'id' => $reservation->getId(),
                'start' => [
                    'year' => $yearStart,
                    'month' => $monthStart,
                    'day' => $dayStart,
                    'hour' => $hourStart,
                    'minute' => $minuteStart
                ],
                'end' => [
                    'year' => $yearEnd,
                    'month' => $monthEnd,
                    'day' => $dayEnd,
                    'hour' => $hourEnd,
                    'minute' => $minuteEnd
                ],
                'timeReservationType' => $reservation->getByTimeReservationType(),
            ];
            $reservationRes [] = $reservationTemp;
        }
        $reservationRes = [
            'reservations' => $reservationRes
        ];
        return new JsonResponse($reservationRes, 200);
    }
}