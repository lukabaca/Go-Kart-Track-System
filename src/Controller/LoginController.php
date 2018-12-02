<?php
namespace App\Controller;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        if($this->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('dashboard/index');
        }
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUserEmail = $authenticationUtils->getLastUsername();
        $view = $afterLogout ? 'views/controllers/login/logout.html.twig' : 'views/controllers/login/index.html.twig';
        return $this->render($view, [
            'lastUserEmail' => $lastUserEmail,
            'error'         => $error,
        ]);
    }
}