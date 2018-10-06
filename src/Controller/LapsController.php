<?php
/**
 * Created by PhpStorm.
 * User: Luka
 * Date: 2018-10-06
 * Time: 14:54
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LapsController extends Controller
{
    /**
     * @Route("/laps", name="laps")
     */
    public function login(Request $request)
    {
        return $this->render('views/controllers/laps/index.html.twig', array(
        ));
    }
}