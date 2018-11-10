<?php
/**
 * Created by PhpStorm.
 * User: Luka
 * Date: 2018-11-10
 * Time: 20:52
 */
namespace App\Controller;
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
        return $this->render('views/controllers/trackInfo/index.html.twig', []
        );
    }
}