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
use DateTime;
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

    /**
     * @Route("/test", name="test")
     */
    public function test(Request $request)
    {
//        $user = new User('a@gmail.com', '1234', 'lukasz', 'blaszczyk', new DateTime('2011-01-01T15:03:01.012345Z'),
//            '123123', '123123', '12312213');
//        $em = $this->getDoctrine()->getManager();
//        $em->persist($user);//tell doctrine you want to save to database, but not query yet
//        $em->flush();//exetues the query

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find(1);

        if (!$user)
        {
           echo 'blad';
        }

        var_dump($user);
        exit();
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