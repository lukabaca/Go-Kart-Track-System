<?php
/**
 * Created by PhpStorm.
 * User: Luka
 * Date: 2018-10-26
 * Time: 20:18
 */
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
class StatusCodeController extends Controller
{
    /**
     * @Route("/status500", name="/status500")
     */
    public function indexAction(Request $request)
    {
        return $this->render('views/alerts/500.html.twig', []
        );
    }
}