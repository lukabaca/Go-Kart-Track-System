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
class RegistrationController extends Controller
{
    /**
     * @Route("/registration", name="registration")
     */
    public function indexAction(Request $request)
    {
        return $this->render('views/controllers/registration/index.html.twig', []);
    }

    private function handleForm(Request $request, User $user)
    {
        $userLoginForm = $this->createForm(UserType::class, $user);

        $userLoginForm->handleRequest($request);

        if($userLoginForm->isSubmitted() && $userLoginForm->isValid())
        {

            $login = $user->getLogin();
            $password = $user->getPassword();

            trim($login);
            trim($password);


            $authorization = $this->getUserFromAPI($login, $password);

            if($authorization) {

                $userInfo = $this->getLoggedUserInfo($authorization);

                if($userInfo) {
                    $userName = $userInfo['name'];

                    $userName = $userName ? $this->getProperFormatUserName($userName) : '';

                    $session = new Session();

                    $session->set(SessionController::getAuthBearerVariableName(), $authorization);
                    $session->set(SessionController::getUserNameVariableName(), $userName);

                    return $this->redirect('meeting');
                } else {
                    return $this->render('controllers/common/guzzleException.html.twig', [
                    ]);
                }

            } else {

                return $this->render('controllers/login/loginError.html.twig', array(
                    'userLoginForm' => $userLoginForm->createView(),
                    'errorMessage' => $this->errorMessage
                ));
            }

        }

        if($this->isLoggedOut) {
            return $this->render('controllers/login/logout.html.twig', array(
                'logoutMessage' => $this->logoutMessage,
                'userLoginForm' => $userLoginForm->createView(),
            ));
        }

        return $this->render('controllers/login/index.html.twig', [
            'userLoginForm' => $userLoginForm->createView(),

        ]);
    }
}