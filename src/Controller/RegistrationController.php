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
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends Controller
{
    /**
     * @Route("/registration", name="registration")
     */
    public function indexAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
          $user = new User();
          return $this->handleForm($request, $user, $passwordEncoder);
//        return $this->render('views/controllers/registration/index.html.twig', []);
    }

    /**
     * @Route("/test", name="test")
     */
    public function test(Request $request, UserPasswordEncoderInterface $encoder)
    {
//        $user = new User('a@gmail.com', '1234', 'lukasz', 'blaszczyk', new DateTime('2011-01-01T15:03:01.012345Z'),
//            '123123', '123123', '12312213');
//        $em = $this->getDoctrine()->getManager();
//        $em->persist($user);//tell doctrine you want to save to database, but not query yet
//        $em->flush();//exetues the query

//        $user = $this->getDoctrine()
//            ->getRepository(User::class)
//            ->find(2);
//
//        if (!$user)
//        {
//           echo 'blad';
//        }
        $user = new User();
        $user->setId(1);
        $encoded = $encoder->encodePassword($user, '1234');

        $user->setPassword($encoded);
        var_dump($user);
        exit();
    }

    private function handleForm(Request $request, User $user, UserPasswordEncoderInterface $encoder)
    {
        $userLoginForm = $this->createForm(UserType::class, $user);

        $userLoginForm->handleRequest($request);

        if($userLoginForm->isSubmitted() && $userLoginForm->isValid())
        {
            $encodedPassword = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encodedPassword);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            //tutaj przekieruj na dashboard
            return $this->render('views/controllers/login/index.html.twig', []);
        }

        return $this->render('views/controllers/registration/index.html.twig', [
            'userLoginForm' => $userLoginForm->createView(),

        ]);
    }
}