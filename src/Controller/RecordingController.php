<?php
/**
 * Created by PhpStorm.
 * User: Luka
 * Date: 2018-10-09
 * Time: 19:25
 */

namespace App\Controller;
use App\Entity\Recording;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class RecordingController extends Controller
{
    /**
     * @Route("/recording/index", name="recording/index")
     */
    public function indexAction(Request $request)
    {
        return $this->render('views/controllers/recording/index.html.twig', []
        );
    }
    /**
     * @Route("/test", name="test")
     */
    public function test(Request $request)
    {


        $roles = $this->getDoctrine()
            ->getRepository(Recording::class)
            ->find(1);

        if (!$roles)
        {
            echo 'GGG';
        }
        $test = $roles->getRecordingLink();
        $test2 = $roles->getUser()->getName();
        var_dump($test2);
        exit();

    }
}