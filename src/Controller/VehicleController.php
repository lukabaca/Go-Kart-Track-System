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
use function MongoDB\BSON\toJSON;
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
            $res = $this->getDoctrine()->getRepository(Kart::class)->
            getKarts($start, $length, $orderColumnName, $orderDir, $searchValue);
            $recordsTotalCount = count($this->getDoctrine()->getRepository(Kart::class)->findAll());
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
     * @Route("/vehicle/manageVehicles", name="/vehicle/manageVehicles")
     * @IsGranted("ROLE_ADMIN")
     */
    public function manageVehiclesAction(Request $request) {
        return $this->render('views/controllers/vehicle/manageVehicles.html.twig' ,[
        ]);
    }

    /**
     * @Route("/vehicle/editKartAvailability/{id}/{availability}", name="/vehicle/editKartAvailability/{id}/{availability}")
     * @IsGranted("ROLE_ADMIN")
     */
    public function editKartAvailabilityAction(Request $request, $id, $availability) {
        $kart = $this->getDoctrine()->getRepository(Kart::class)->find($id);
        if(!$kart) {
            return new JsonResponse([], 404);
        }
        if ($availability != 0 && $availability != 1) {
            return new JsonResponse(['Availabilty value must be 0 or 1'], 400);
        }
        $kart->setAvailability($availability);
        $em = $this->getDoctrine()->getManager();
        $em->persist($kart);
        $em->flush();
        $kart = [
          'id' => $kart->getId(),
            'availability' => $kart->getAvailability(),
            'prize' => $kart->getPrize(),
            'name' => $kart->getName(),
            'description' => $kart->getDescription(),
            'file' => $kart->getFile(),
        ];
        return new JsonResponse($kart, 200);
    }
    /**
     * @Route("/vehicle/addKart/{id}", name="/vehicle/addKart", defaults={"id"=null})
     * @IsGranted("ROLE_ADMIN")
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
                $isEditingKart = true;
            }catch (FileException $e) {
                $kart->setFile(null);
            }
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
                        $fileSystem = new Filesystem();
                        $fileSystem->remove($this->getParameter('kartImage_directory') . '/' . $fileTempName);
                    }
                } else {
                    if($isEditingKart) {
                        $kart->setFile($fileTempName);
                    }
                }
            } catch (FileException $e) {
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
        return md5(uniqid());
    }
}