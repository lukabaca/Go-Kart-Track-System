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
use phpDocumentor\Reflection\Types\Iterable_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
        if ($request->request->get('recordingData')) {
            $recordingTemp = json_decode($request->request->get('recordingData'), true);

            $recording = new Recording();
            $recording->setTitle($recordingTemp['title']);
            $recording->setRecordingLink($recordingTemp['link']);
            $user = $this->getUser();

            $recording->setUser($user);

            $em = $this->getDoctrine()->getManager();
            $em->persist($recording);
            $em->flush();

            return new JsonResponse([], 201);
//            return $this->handleForm($request, $recording);
        } else {
            return new JsonResponse([], 500);
        }
    }
    private function getYoutubeEmbedUrl($url){

        $urlParts   = explode('/', $url);
        $vidid      = explode( '&', str_replace('watch?v=', '', end($urlParts) ) );

        return 'https://www.youtube.com/embed/' . $vidid[0] ;
    }
    private function handleForm(Request $request, $recording)
    {
//        $recording = new Recording();
        $recordingLoginForm = $this->createForm(RecordingType::class, $recording);

//        $recordingLoginForm->handleRequest($request);

        $recordingLoginForm->submit($recording);

        if(!$recordingLoginForm->isSubmitted()) {
            print_r('nie jest submitted');
            exit();
        }

        if($recordingLoginForm->isSubmitted() && $recordingLoginForm->isValid())
        {

            return new JsonResponse(array('a' => 'b'), 200);
        }
        return new JsonResponse(array('c' => 'd'), 400);

    }

    /**
     * @Route("/test", name="test")
     */
    public function test(Request $request)
    {


//        $roles = $this->getDoctrine()
//            ->getRepository(Recording::class)
//            ->find(1);
//
//        if (!$roles)
//        {
//            echo 'GGG';
//        }
//        $test = $roles->getRecordingLink();
//        $test2 = $roles->getUser()->getName();
        $testYT = $this->getYoutubeEmbedUrl('https://www.youtube.com/watch?v=kHbQr6Xsy8U');
        print_r($testYT);
        exit();

    }
}