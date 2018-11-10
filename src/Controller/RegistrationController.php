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
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class RegistrationController extends Controller
{
    /**
     * @Route("/registration/index", name="registration/index")
     */
    public function indexAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
          $user = new User();
          return $this->handleForm($request, $user, $passwordEncoder);
    }
    /**
     * @Route("/registration/editUserData", name="registration/editUserData")
     */
    public function editUserDataAction(Request $request, UserPasswordEncoderInterface $passwordEncoder, UserInterface $user)
    {
        $user = $this->getUser();
        $userID = $user->getId();
        $repository = $this->getDoctrine()->getRepository(User::class);
        $userToEdit = $repository->find($userID);
        if(!$userToEdit) {
            throw $this->createNotFoundException(
                'No user found for id '.$userID
            );
        }
        return $this->handleForm($request, $userToEdit, $passwordEncoder, true);
    }
    private function handleForm(Request $request, User $user, UserPasswordEncoderInterface $encoder, $isEditingUser = false)
    {
        $userLoginForm = $this->createForm(UserType::class, $user);
        $userLoginForm->handleRequest($request);
        if($userLoginForm->isSubmitted() && $userLoginForm->isValid())
        {
            $encodedPassword = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encodedPassword);
            $role = $this->getDoctrine()->getRepository(Role::class)->findOneBy(array('name' => 'ROLE_USER'));
            if(!$role) {
                return $this->render('views/alerts/500.html.twig', []);
            }
            $roleArray = new ArrayCollection();
            $roleArray [] = $role;
            $user->setRoles($roleArray);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            if(!$isEditingUser) {
                return $this->render('views/controllers/registration/index.html.twig', [
                    'userLoginForm' => $userLoginForm->createView(),
                    'sucessfulRegistration' => true
                ]);
            }
            //tutaj przekieruj na dashboard
            return $this->render('views/controllers/dashboard/index.html.twig', []);
        }
        if(!$isEditingUser) {
            return $this->render('views/controllers/registration/index.html.twig', [
                'userLoginForm' => $userLoginForm->createView(),
                'sucessfulRegistration' => false
            ]);
        } else {
            return $this->render('views/controllers/registration/editUser.html.twig', [
                'userLoginForm' => $userLoginForm->createView(),
            ]);
        }
    }
}