<?php
/**
 * Created by PhpStorm.
 * User: Luka
 * Date: 2018-10-03
 * Time: 17:50
 */

namespace App\Controller;


use App\Entity\Role;
use App\Entity\User;
use App\Entity\UserRoles;
use App\Form\UserType;
use App\Repository\RoleRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class RegistrationController extends Controller
{
    /**
     * @Route("/registration", name="registration")
     */
    public function indexAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
          $user = new User();
          return $this->handleForm($request, $user, $passwordEncoder);
    }

    /**
     * @Route("/editUserData", name="editUserData")
     */
    public function editUserDataAction(Request $request, UserPasswordEncoderInterface $passwordEncoder, UserInterface $user)
    {

//        print_r($user->getBirthDate());
//
//        exit();
        $userID = $user->getId();
        $repository = $this->getDoctrine()->getRepository(User::class);
        $user = $repository->find($userID);

        if(!$user) {
            throw $this->createNotFoundException(
                'No user found for id '.$userID
            );
        }

        return $this->handleForm($request, $user, $passwordEncoder, true);
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

        $roles = $this->getDoctrine()
            ->getRepository(User::class)
            ->loadUserByUsername('jan@gmail.com');

        if (!$roles)
        {
           echo 'GGG';
        }

        var_dump($roles);
        exit();



//        $user = new User();
//        $user->setId(1);
//        $encoded = $encoder->encodePassword($user, '1234');
//
//        $user->setPassword($encoded);
//        var_dump($user);
//        exit();
    }

    private function handleForm(Request $request, User $user, UserPasswordEncoderInterface $encoder, $isEditingUser = false)
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

            if(!$isEditingUser) {
                $em->getRepository(UserRoles::class)->insertUserAndRolesIDs($user->getId(), 1);
                return $this->render('views/controllers/login/index.html.twig', []);
            }
            //tutaj przekieruj na dashboard
            return $this->render('views/controllers/laps/index.html.twig', []);

        }

        if(!$isEditingUser) {
            return $this->render('views/controllers/registration/index.html.twig', [
                'userLoginForm' => $userLoginForm->createView(),
            ]);
        } else {
            return $this->render('views/controllers/registration/editUser.html.twig', [
                'userLoginForm' => $userLoginForm->createView(),
            ]);
        }
    }
}