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
        $trackInfo = $this->getDoctrine()->getRepository(TrackInfo::class)->find(1);
        if(!$trackInfo) {
            return $this->render('views/alerts/404.index.html.twig', []
            );
        }
//        $trackInfo = [
//            'street' => $trackInfoTemp->getStreet() ? $trackInfoTemp->getStreet() : '',
//            'city' => $trackInfoTemp->getCity() ? $trackInfoTemp->getCity() : '',
//            'telephoneNumber' => $trackInfoTemp->getTelephoneNumber() ? $trackInfoTemp->getTelephoneNumber() : '',
//            'hourStart' => $trackInfoTemp->getHourStart() ? $trackInfoTemp->getHourStart() : '',
//            'hourEnd' => $trackInfoTemp->getHourEnd() ? $trackInfoTemp->getHourEnd() : '',
//            'facebookLink' => $trackInfoTemp->getFacebookLink() ? $trackInfoTemp->getFacebookLink() : '',
//            'instagramLink' => $trackInfoTemp->getInstagramLink() ? $trackInfoTemp->getInstagramLink() : '',
//        ];
        return $this->render('views/controllers/trackInfo/index.html.twig', [
            'trackInfo' => $trackInfo,
        ]
        );
    }
}