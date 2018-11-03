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
     * @Route("/login/{afterLogout}", name="login")
     * @Route("/", name="default")
     */
    public function login(AuthenticationUtils $authenticationUtils, $afterLogout = false)
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUserEmail = $authenticationUtils->getLastUsername();
        $view = $afterLogout ? 'views/controllers/login/logout.html.twig' : 'views/controllers/login/index.html.twig';
        return $this->render($view, [
            'lastUserEmail' => $lastUserEmail,
            'error'         => $error,
        ]);
    }
}