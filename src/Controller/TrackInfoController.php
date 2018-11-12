<?php
/**
 * Created by PhpStorm.
 * User: Luka
 * Date: 2018-11-10
 * Time: 20:52
 */
namespace App\Controller;
use App\Entity\trackConfig\TrackInfo;
use App\Form\TrackInfoType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
class TrackInfoController extends Controller
{
    /**
     * @Route("/trackInfo/index", name="trackInfo/index")
     */
    public function indexAction(Request $request)
    {
        $trackInfo = $this->getDoctrine()->getRepository(TrackInfo::class)->find(1);
        return $this->render('views/controllers/trackInfo/index.html.twig', [
            'trackInfo' => $trackInfo,
        ]
        );
    }
    /**
     * @Route("/trackInfo/addTrackInfo", name="/trackInfo/addTrackInfo")
     */
    public function addTrackInfoAction(Request $request)
    {
        $trackInfo = $this->getDoctrine()->getRepository(TrackInfo::class)->find(1);
        if(!$trackInfo) {
            $trackInfo = new TrackInfo();
        }
        $trackInfoForm = $this->createForm(TrackInfoType::class, $trackInfo);
        $trackInfoForm->handleRequest($request);
        if ($trackInfoForm->isSubmitted() && $trackInfoForm->isValid()) {
            print_r('submiteed');
        }
        return $this->render('views/controllers/trackInfo/addTrackInfo.html.twig', [
                'trackInfoForm' => $trackInfoForm->createView(),
            ]
        );
    }
}