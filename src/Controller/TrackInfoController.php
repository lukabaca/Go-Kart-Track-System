<?php
/**
 * Created by PhpStorm.
 * User: Luka
 * Date: 2018-11-10
 * Time: 20:52
 */
namespace App\Controller;
use App\Entity\trackConfig\TrackInfo;
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
        $trackInfo = $this->getDoctrine()->getRepository(TrackInfo::class)->findAll();
        if(!$trackInfo) {
            $trackInfo = null;
        } else {
            $trackInfo = $trackInfo[0];
        }
        return $this->render('views/controllers/trackInfo/index.html.twig', [
            'trackInfo' => $trackInfo,
        ]
        );
    }
}