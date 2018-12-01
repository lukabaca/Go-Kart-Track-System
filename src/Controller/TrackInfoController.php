<?php
namespace App\Controller;
use App\Entity\trackConfig\TrackInfo;
use App\Form\TrackInfoType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
     * @IsGranted("ROLE_ADMIN")
     */
    public function addTrackInfoAction(Request $request)
    {
        $isSuccessful = false;
        $trackInfo = $this->getDoctrine()->getRepository(TrackInfo::class)->find(1);
        if(!$trackInfo) {
            $trackInfo = new TrackInfo();
        }
        $trackInfoForm = $this->createForm(TrackInfoType::class, $trackInfo);
        $trackInfoForm->handleRequest($request);
        if ($trackInfoForm->isSubmitted() && $trackInfoForm->isValid()) {
           $em = $this->getDoctrine()->getManager();
           $em->persist($trackInfo);
           $em->flush();
           $isSuccessful = true;
        }
        return $this->render('views/controllers/trackInfo/addTrackInfo.html.twig', [
                'trackInfoForm' => $trackInfoForm->createView(),
                'isSuccessful' => $isSuccessful,
            ]
        );
    }
}