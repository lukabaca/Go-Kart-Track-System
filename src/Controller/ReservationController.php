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

        $isReservationValid = $this->getDoctrine()->getManager()->getRepository(Reservation::class)->makeReservation($this->getUser()->getId(),
            '2018-10-27 18:00', '2018-10-27 18:30', 50);
        if(!$isReservationValid) {
            return new JsonResponse([], 404);
        }
        print_r($isReservationValid->getId());
        exit();

       return new JsonResponse($isReservationValid, 200);
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
                return new JsonResponse([], 409);
            }
            if($isReservationValid == 2) {
                return new JsonResponse([], 400);
            }
            $reservation = $this->getDoctrine()->getManager()->getRepository(Reservation::class)->makeReservation($this->getUser()->getId(),
                $startDate, $endDate, $cost);
            if(!$reservation) {
                return new JsonResponse(['a'], 500);
            }
            $reservationId = $reservation->getId();
            foreach ($kartIds as $kartId) {
                $reservationAndKartIds = $this->getDoctrine()->getManager()->getRepository(Reservation::class)->insertReservationAndKartIds($reservationId, $kartId);
                if(!$reservationAndKartIds) {
                    //w tym wypadku musisz usunac rezerwacje która się dodałą, masz jej reservationId
                    return new JsonResponse([$kartId], 500);
                }
            }
            $reservation = [
                'id' => $reservation->getId(),
                'startDate' => $reservation->getStartDate(),
                'endDate' => $reservation->getEndDate(),
                'cost' => $reservation->getCost()
            ];
            return new JsonResponse($reservation, 200);
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
     * @Route("/reservation/calendar", name="reservation/calendar")
     */
    public function calendarAction(Request $request)
    {

        return $this->render('views/controllers/reservation/calendar.html.twig', []
        );
    }

}