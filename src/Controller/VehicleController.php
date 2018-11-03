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
use App\Form\KartType;
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

        $karts = $this->getDoctrine()->getRepository(Kart::class)->findAll();
        if(!$karts) {
            return $this->render('views/alerts/404.html.twig' ,[
            ]);
        }
        return $this->render('views/controllers/vehicle/index.html.twig' ,[
            'karts' => $karts
        ]);
    }

    /**
     * @Route("/vehicle/manageVehicles/{id}", name="/vehicle/manageVehicles", defaults={"id"=null})
     */
    public function manageVehiclesAction(Request $request, $id) {
        if($id) {
            $kart = $this->getDoctrine()->getRepository(Kart::class)->find($id);
            $kartTechnicalDataTemp = $kart->getKartTechnicalData();
            $kart->setKartTechnicalData($kartTechnicalDataTemp[0]);
            if(!$kart) {
                return $this->render('views/alerts/404.html.twig', []);
            }
        } else {
            $kart = new Kart();
        }
        $kartForm = $this->createForm(KartType::class, $kart);
        $kartForm->handleRequest($request);
        if($kartForm->isSubmitted() && $kartForm->isValid()) {
            print_r($kart);
            exit();
        }
        return $this->render('views/controllers/vehicle/manageVehicles.html.twig' ,[
            'kartForm' => $kartForm->createView()
        ]);
    }
}