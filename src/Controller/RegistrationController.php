<?php
/**
 * Created by PhpStorm.
 * User: Luka
 * Date: 2018-10-03
 * Time: 17:50
 */

namespace App\Controller;


use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
class RegistrationController extends Controller
{
    /**
     * @Route("/registration", name="registration")
     */
    public function indexAction(Request $request)
    {
          $user = new User();
          return $this->handleForm($request, $user);
//        return $this->render('views/controllers/registration/index.html.twig', []);
    }

    private function handleForm(Request $request, User $user)
    {
        $userLoginForm = $this->createForm(UserType::class, $user);

        $userLoginForm->handleRequest($request);

        if($userLoginForm->isSubmitted() && $userLoginForm->isValid())
        {
            $name = $user->getName();
            var_dump($name);
            exit();

        }

        return $this->render('views/controllers/registration/index.html.twig', [
            'userLoginForm' => $userLoginForm->createView(),

        ]);
    }
}