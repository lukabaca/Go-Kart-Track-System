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
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
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
        $karts = [];
        foreach ($kartsTemp as $kartTemp) {
            if($kartTemp->getAvailability()) {
                $karts [] = $kartTemp;
            }
//            print_r($kartTemp->getFile());
        }
//        exit();
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
        $isEditingKart = false;
        if($id) {
            $kart = $this->getDoctrine()->getRepository(Kart::class)->find($id);
            if(!$kart) {
                return $this->render('views/alerts/404.html.twig', []);
            }
            try {
                $fileTemp = new File($this->getParameter('kartImage_directory') . '/' . $kart->getFile());
                $fileTempName =  $kart->getFile();
                $kart->setFile($fileTemp);
//                print_r($kart->getFile());
//                exit();
            }catch (FileException $e) {

            }
            $isEditingKart = true;
            $actionMode = 'Edytuj gokart';
        } else {
            $kart = new Kart();
            $actionMode = 'Dodaj gokart';
        }
        $kartForm = $this->createForm(KartType::class, $kart);
        $kartForm->handleRequest($request);
        if($kartForm->isSubmitted() && $kartForm->isValid()) {
            try {
                $file = $kart->getFile();
                if($file) {
//                    print_r('aaa');
                    $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();
                    $file->move($this->getParameter('kartImage_directory'), $fileName);
                    $kart->setFile($fileName);
                } else {
                    if($isEditingKart) {
                        $kart->setFile($fileTempName);
                    }
                }
            } catch (FileException $e) {
                print_r('expcetion przy filu');
            }
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

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }
}