<?php
/**
 * Created by PhpStorm.
 * User: Luka
 * Date: 2018-10-20
 * Time: 12:59
 */

namespace App\Controller;


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
        return $this->render('views/controllers/reservation/index.html.twig', []
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
}