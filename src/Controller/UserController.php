<?php
/**
 * Created by PhpStorm.
 * User: Luka
 * Date: 2018-11-09
 * Time: 16:41
 */

namespace App\Controller;
use App\Entity\User;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
     * @Route("/user/editUserRole/{id}", name="/user/editUserRole/{id}")
     * @IsGranted("ROLE_ADMIN")
     */
    public function editUserRoleAction(Request $request, $id)
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        if(!$user) {
            return $this->render('views/alerts/404.html.twig', []);
        }
        return $this->render('views/controllers/user/editUserRole.html.twig', [
            'user' => $user
        ]);
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
}