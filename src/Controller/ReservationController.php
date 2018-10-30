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
use App\Repository\trackConfig\RideTimeDictionaryRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;


class ReservationController extends Controller
{
    /**
     * @Route("/reservation/index", name="reservation/index")
     */
    public function indexAction(Request $request)
    {
//        godzina rozpoczecia, to godzina otwarcia toru dla klientow, to tez mozesz trzymac w bazie
        return $this->render('views/controllers/reservation/index.html.twig', [
            ]
        );
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
     * @Route("/reservation/isReservationValid", name="/reservation/isReservationValid")
     */
    public function isReservationValidAction(Request $request)
    {
        $prize = $this->getDoctrine()->getManager()->getRepository(Reservation::class)->getKartPrizeByNumberOfRides(1, 2);
        if(!$prize) {
            return new JsonResponse([], 404);
        }
        if($prize == -1) {
            return new JsonResponse(['Bad input value'], 400);
        }
        print_r($prize);
        exit();

       return new JsonResponse([], 500);
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
            $kartIds = $reservationData->{'karts'};
            $isReservationValid = $this->getDoctrine()->getManager()->getRepository(Reservation::class)->isReservationValid($this->getUser()->getId(),
                $startDate, $endDate, $cost);
            if($isReservationValid == 0) {
                return new JsonResponse(['Other reservation in this hour exists'], 409);
            }
            if($isReservationValid == 2) {
                return new JsonResponse(['Wrong dates (too early or too late)'], 400);
            }
            $reservation = $this->getDoctrine()->getManager()->getRepository(Reservation::class)->makeReservation($this->getUser()->getId(),
                $startDate, $endDate, $cost);
            if(!$reservation) {
                return new JsonResponse(['Error while adding reservation'], 500);
            }
            $reservationId = $reservation->getId();
            foreach ($kartIds as $kartId) {
                $reservationAndKartIds = $this->getDoctrine()->getManager()->getRepository(Reservation::class)->insertReservationAndKartIds($reservationId, $kartId);
                if(!$reservationAndKartIds) {
                    //w tym wypadku musisz usunac rezerwacje która się dodałą, masz jej reservationId
                    return new JsonResponse(['Error while progressing reservation'], 500);
                }
            }
            $reservation = [
                'id' => $reservation->getId(),
                'startDate' => $reservation->getStartDate(),
                'endDate' => $reservation->getEndDate(),
                'cost' => $reservation->getCost()
            ];
            return new JsonResponse($reservation, 201);
        } else {
            return new JsonResponse([], 500);
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
        //sprawdz czy dla id tej rezerwacji nalezy do zalogowanego uzytkwonika, jesli tak to pokaz szczegoly rezerwacji
        //jesli nie to przekieruj na nie masz dostepu do tej strony
        $reservation = $this->getDoctrine()->getManager()->getRepository(Reservation::class)->find($id);
        if(!$reservation) {
            return $this->render('views/alerts/404.html.twig', []);
        }
        $userIdForReservation = $reservation->getUser()->getId();
        if($userIdForReservation != $this->getUser()->getId()) {
            //nie mozesz przegladac czyich szczegolow rezerwacji
        }
        $kartsInReservation = $this->getDoctrine()->getManager()->getRepository(Kart::class)->getKartsInReservation($id);
        if(!$kartsInReservation) {

        }
        $kartsRes = [];
        foreach ($kartsInReservation as $kart) {
            $kartTemp = [
                'id' => $kart->getId(),
                'name' => $kart->getName(),
                'prize' => $kart->getPrize(),
                'availability' => $kart->getAvailability(),
            ];
            $kartsRes [] = $kartTemp;
        }
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
            ];
            $reservationRes [] = $reservationTemp;
        }
        $reservationRes = [
            'reservations' => $reservationRes
        ];
        return new JsonResponse($reservationRes, 200);
    }
}