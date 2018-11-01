<?php
/**
 * Created by PhpStorm.
 * User: Luka
 * Date: 2018-10-16
 * Time: 20:50
 */

namespace App\Controller;
use App\Entity\Kart;
use App\Entity\KartTechnicalData;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VehicleController extends Controller
{
    /**
     * @Route("/vehicle/index", name="vehicle/index")
     */
    public function indexAction(Request $request) {

        $kartsTemp = $this->getDoctrine()->getRepository(Kart::class)->findAll();

        if(!$kartsTemp) {
            echo 'blad';
        }

        return $this->render('views/controllers/vehicle/index.html.twig' ,[
            'karts' => $kartsTemp
        ]);
    }

    /**
     * @Route("/vehicle/test", name="vehicle/test")
     */
    public function aaAction(Request $request) {

        $kartsTemp = $this->getDoctrine()->getRepository(KartTechnicalData::class)->findAll();

        if(!$kartsTemp) {
            echo 'blad';
        }
        print_r($kartsTemp);
        exit();
        return $this->render('views/controllers/vehicle/index.html.twig' ,[
            'karts' => $kartsTemp
        ]);
    }
}