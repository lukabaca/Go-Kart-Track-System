<?php
/**
 * Created by PhpStorm.
 * User: Luka
 * Date: 2018-11-09
 * Time: 16:41
 */

namespace App\Controller;
use App\Entity\Role;
use App\Entity\User;
use App\Form\UserType;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends Controller
{
    /**
     * @Route("/user/manageUsers", name="/user/manageUsers")
     */
    public function manageUsersAction(Request $request)
    {
       return $this->render('views/controllers/user/manageUsers.html.twig', []);
    }
    /**
     * @Route("/user/getRoles", name="/user/getRoles")
     */
    public function getRolesAction(Request $request)
    {
        $rolesTemp = $this->getDoctrine()->getRepository(Role::class)->findAll();
        if(!$rolesTemp) {
            return new JsonResponse([], 404);
        }
        $roles = [];
        foreach ($rolesTemp as $roleTemp) {
            $role = [
                'id' => $roleTemp->getId(),
                'name' => $roleTemp->getName(),
            ];
            $roles [] = $role;
        }
        return new JsonResponse($roles, 200);
    }

    /**
 * @Route("/user/admin/userDetails/{id}", name="/user/admin/userDetails/{id}")
 * @IsGranted("ROLE_ADMIN")
 */
    public function userDetailsAction(Request $request, $id)
    {
        $roleDictionary = [
            'ROLE_USER' => 'Użytkownik',
            'ROLE_ADMIN' => 'Administrator',
        ];
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        if(!$user) {
            return $this->render('views/alerts/404.html.twig', []);
        }
        $rolesTemp = $this->getDoctrine()->getRepository(Role::class)->findAll();
        if(!$rolesTemp) {
            return $this->render('views/alerts/404.html.twig', []);
        }
        $roles = [];
        foreach ($rolesTemp as $roleTemp) {
            $roleName = $roleDictionary[$roleTemp->getName()];
            $role = [
                'id' => $roleTemp->getId(),
                'name' => $roleName,
            ];
            $roles [] = $role;
        }
        return $this->render('views/controllers/user/editUserRole.html.twig', [
            'user' => $user,
            'roles' => $roles,
            'roles' => $roles,
        ]);
    }
    /**
     * @Route("/user/editUserRole/{userId}/{roleId}", name="/user/editUserRole/{userId}/{roleId}")
     * @IsGranted("ROLE_ADMIN")
     */
    public function editUserRoleAction(Request $request, $userId, $roleId)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($userId);
        if(!$user) {
            return new JsonResponse(['user not found'], 404);
        }
        $role = $this->getDoctrine()->getRepository(Role::class)->find($roleId);
        if(!$role) {
            return new JsonResponse(['role not found'], 404);
        }
        $array = new ArrayCollection();
        $array [] = $role;
        $user->setRoles($array);
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        return new JsonResponse($user, 200);
    }
    /**
     * @Route("/user/datatable", name="user/datatable")
     */
    public function datatableAction(Request $request)
    {
        if ($request->getMethod() == 'POST') {
            $draw = intval($request->request->get('draw'));
            $start = $request->request->get('start');
            $length = $request->request->get('length');
            $search = $request->request->get('search');
            $orders = $request->request->get('order');
            $columns = $request->request->get('columns');
            $orderColumn = $orders[0]['column'];
            $orderDir = $orders[0]['dir'];
            $searchValue = $search['value'];
            foreach ($columns as $key => $column)
            {
                if ($orderColumn == $key) {
                    $orderColumnName = $column['name'];
                }
            }
            $res = $this->getDoctrine()->getRepository(User::class)->
            getUsers($start, $length, $orderColumnName, $orderDir, $searchValue);
            $recordsTotalCount = count($this->getDoctrine()->getRepository(User::class)->findAll());
            $response = [
                "draw" => $draw,
                "recordsTotal" => $recordsTotalCount,
                "recordsFiltered" => $recordsTotalCount,
                "data" => $res,
            ];
            return new JsonResponse($response, 200);
        } else {
            return new JsonResponse([], 400);
        }
    }

    /**
     * @Route("/user/editUserData", name="user/editUserData")
     */
    public function editUserDataAction(Request $request, UserPasswordEncoderInterface $encoder)
    {
        try {
            $dataSuccessChange = false;
            $user = $this->getUser();
            $userForm = $this->createForm(UserType::class, $user);
            $userForm->handleRequest($request);
            if ($userForm->isSubmitted() && $userForm->isValid()) {
                $encodedPassword = $encoder->encodePassword($user, $user->getPassword());
                $user->setPassword($encodedPassword);
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                $dataSuccessChange = true;
            }
            return $this->render('views/controllers/user/editUser.html.twig', [
                'dataSuccessChange' => $dataSuccessChange,
                'userForm' => $userForm->createView(),
            ]);
        }catch (Exception $e) {
            return $this->render('views/alerts/500.html.twig', [
            ]);
        }
    }
}