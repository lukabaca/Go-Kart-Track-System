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

        return $this->render('views/controllers/reservation/index.html.twig', []
        );
    }
}