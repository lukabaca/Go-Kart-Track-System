<?php
/**
 * Created by PhpStorm.
 * User: Luka
 * Date: 2018-10-09
 * Time: 19:25
 */

namespace App\Controller;
use App\Entity\Recording;
use App\Entity\User;
use App\Form\RecordingType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class RecordingController extends Controller
{
    /**
     * @Route("/recording/index", name="recording/index")
     */
    public function indexAction(Request $request)
    {
        $recording = new Recording();
//        return $this->handleForm($request, $recording);
        $recordingLoginForm = $this->createForm(RecordingType::class, $recording);
        return $this->render('views/controllers/recording/index.html.twig', [
                'recordingLoginForm' => $recordingLoginForm->createView(),
            ]
        );
    }

    /**
     * @Route("/recording/addRecording", name="recording/addRecording")
     */
    public function addRecordingAction(Request $request)
    {

    }

    private function handleForm(Request $request, Recording $recording)
    {
        $recordingLoginForm = $this->createForm(RecordingType::class, $recording);

        $recordingLoginForm->handleRequest($request);

        if($recordingLoginForm->isSubmitted() && $recordingLoginForm->isValid())
        {


            //tutaj przekieruj na dashboard
//            return $this->render('views/controllers/laps/index.html.twig', []);
            return new JsonResponse(array('a' => 'b'), 200);
        }
        return new JsonResponse(array('c' => 'd'), 400);
//        return $this->render('views/controllers/registration/editUser.html.twig', [
//            'userLoginForm' => $recordingLoginForm->createView(),
//        ]);
    }
    /**
     * @Route("/test", name="test")
     */
    public function test(Request $request)
    {


        $roles = $this->getDoctrine()
            ->getRepository(Recording::class)
            ->find(1);

        if (!$roles)
        {
            echo 'GGG';
        }
        $test = $roles->getRecordingLink();
        $test2 = $roles->getUser()->getName();
        var_dump($test2);
        exit();

    }
}