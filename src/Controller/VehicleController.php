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
use Doctrine\Common\Collections\ArrayCollection;
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
        return $this->render('views/controllers/vehicle/index.html.twig' ,[
            'karts' => $karts
        ]);
    }

    /**
     * @Route("/vehicle/manageVehicles", name="/vehicle/manageVehicles")
     */
    public function manageVehiclesAction(Request $request) {
        $karts = $this->getDoctrine()->getRepository(Kart::class)->findAll();
        return $this->render('views/controllers/vehicle/manageVehicles.html.twig' ,[
            'karts' => $karts
        ]);
    }

    /**
     * @Route("/vehicle/addKart/{id}", name="/vehicle/addKart", defaults={"id"=null})
     */
    public function addKartAction(Request $request, $id) {
        if($id) {
            $kart = $this->getDoctrine()->getRepository(Kart::class)->find($id);
            if(!$kart) {
                return $this->render('views/alerts/404.html.twig', []);
            }
            $actionMode = 'Edytuj gokart';
        } else {
            $kart = new Kart();
            $actionMode = 'Dodaj gokart';
        }
        $kartForm = $this->createForm(KartType::class, $kart);
        $kartForm->handleRequest($request);
        if($kartForm->isSubmitted() && $kartForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($kart);
            $em->flush();
            $karts = $this->getDoctrine()->getRepository(Kart::class)->findAll();
            return $this->render('views/controllers/vehicle/manageVehicles.html.twig' ,[
                'karts' => $karts
            ]);
        }
        return $this->render('views/controllers/vehicle/addKart.html.twig' ,[
            'kartForm' => $kartForm->createView(),
            'actionMode' => $actionMode,
        ]);
    }
}