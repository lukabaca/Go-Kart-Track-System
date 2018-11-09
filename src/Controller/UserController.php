<?php
/**
 * Created by PhpStorm.
 * User: Luka
 * Date: 2018-11-09
 * Time: 16:41
 */

namespace App\Controller;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends Controller
{
    /**
     * @Route("/user/manageUsers", name="/user/manageUsers")
     */
    public function manageUsersAction(Request $request)
    {
       return $this->render('views/controllers/user/manageUsers.html.twig', []);
    }
}