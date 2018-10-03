<?php
/**
 * Created by PhpStorm.
 * User: Luka
 * Date: 2018-10-03
 * Time: 17:50
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
class LoginController extends Controller
{
    /**
     * @Route("/login", name="login")
     * @Route("/", name="default")
     */
    public function indexAction(Request $request)
    {
        return $this->render('views/controllers/login/index.html.twig', []);
    }
}