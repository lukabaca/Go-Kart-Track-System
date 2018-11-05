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
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
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
        }
        return $this->render('views/controllers/vehicle/index.html.twig' ,[
            'karts' => $karts
        ]);
    }

    /**
     * @Route("/vehicle/datatable", name="vehicle/datatable")
     */
    public function datatableAction(Request $request) {
        if ($request->getMethod() == 'POST')
        {
            $draw = intval($request->request->get('draw'));
            $start = $request->request->get('start');
            $length = $request->request->get('length');
            $search = $request->request->get('search');
            $orders = $request->request->get('order');
            $columns = $request->request->get('columns');
//            return new JsonResponse($orders, 200);
        }
        else // If the request is not a POST one, die hard
            die;
        $orderColumn = $orders[0]['column'];
        $orderDir = $orders[0]['dir'];
        foreach ($columns as $key => $column)
        {
            if ($orderColumn == $key) {
               $orderColumnName = $column['name'];
            }
        }
        $temp = [$orderColumn, $orderDir, $orderColumnName];
        $res = $this->getDoctrine()->getRepository(Kart::class)->
        getKarts($start, $length, $orderColumnName, $orderDir, $search, $columns);
//        print_r($res);
        $recordsFileredCount = count($res);
        $recordsTotalCount = count($this->getDoctrine()->getRepository(Kart::class)->findAll());
        $response = [
            "draw" => $draw,
            "recordsTotal" => $recordsTotalCount,
            "recordsFiltered" => $recordsFileredCount,
            "data" => $res,
        ];
        $test = [
            "draw" => 1,
            "recordsTotal" => 57,
            "recordsFiltered" => 57,
            "data" => [
                [
                   "Angelica",
                    "Ramos",
                   "System Architect",
                ],
            ]
        ];
        return new JsonResponse($response, 200);
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
        $filePath = null;
        $fileTempName = null;
        if($id) {
            $kart = $this->getDoctrine()->getRepository(Kart::class)->find($id);
            if(!$kart) {
                return $this->render('views/alerts/404.html.twig', []);
            }
            try {
                $fileTemp = new File($this->getParameter('kartImage_directory') . '/' . $kart->getFile());
                $fileTempName =  $kart->getFile();
                $filePath = $fileTempName;
                $kart->setFile($fileTemp);
            }catch (FileException $e) {
//                print_r('exception przy wczytywaniu');
                $kart->setFile(null);
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
                    $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();
                    $file->move($this->getParameter('kartImage_directory'), $fileName);
                    $kart->setFile($fileName);
                    if($isEditingKart) {
//                        print_r('wbilo w usuwanie fotek');
                        $fileSystem = new Filesystem();
                        $fileSystem->remove($this->getParameter('kartImage_directory') . '/' . $fileTempName);
                    }
                } else {
                    if($isEditingKart) {
                        $kart->setFile($fileTempName);
                    }
                }
            } catch (FileException $e) {
                //poki co rzucaj 500 server error jak nie uda sie wrzucic foto
                return $this->render('views/alerts/500.html.twig' , []);
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($kart);
            $em->flush();
            $karts = $this->getDoctrine()->getRepository(Kart::class)->findAll();
            return $this->render('views/controllers/vehicle/manageVehicles.html.twig' ,[
                'karts' => $karts,
            ]);
        }
        return $this->render('views/controllers/vehicle/addKart.html.twig' ,[
            'filePath' => $filePath,
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