<?php
/**
 * Created by PhpStorm.
 * User: Luka
 * Date: 2018-10-03
 * Time: 17:50
 */

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     * @Route("/", name="default")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUserEmail = $authenticationUtils->getLastUsername();

        return $this->render('views/controllers/login/index.html.twig', array(
            'lastUserEmail' => $lastUserEmail,
            'error'         => $error,
        ));
    }

    /**
     * @Route("/loginAfterLogout", name="loginAfterLogout")
     */
    public function loginAferLogout(AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUserEmail = $authenticationUtils->getLastUsername();

        return $this->render('views/controllers/login/logout.html.twig', array(
            'lastUserEmail' => $lastUserEmail,
            'error'         => $error,
        ));
    }

}