<?php

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

    private function handleForm(Request $request, User $user, UserPasswordEncoderInterface $encoder)
    {
        $successfulRegistration = false;
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
            $successfulRegistration = true;
        }
        return $this->render('views/controllers/registration/index.html.twig', [
            'userLoginForm' => $userLoginForm->createView(),
            'successfulRegistration' => $successfulRegistration,
        ]);
    }
}