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
        $recordingLoginForm = $this->createForm(RecordingType::class, $recording);

        $recordingsTemp = $this->getDoctrine()->getRepository(Recording::class)->findUserRecordings($this->getUser()->getId());

        $recordings = [];
        foreach ($recordingsTemp as $recording) {
            $recordingTemp = $recording;
            $ytLinkFormatted = $this->getYoutubeEmbedUrl($recordingTemp->getRecordingLink());
            $recordingTemp->setRecordingLink($ytLinkFormatted);
            $recordings [] = $recordingTemp;
        }

        return $this->render('views/controllers/recording/index.html.twig', [
                'recordingLoginForm' => $recordingLoginForm->createView(),
                'recordings' => $recordings
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


            $recording = $this->getDoctrine()->getRepository(Recording::class)->find($recording->getId());

            if(!$recording) {
                return new JsonResponse([], 404);
            }

            $id = $recording->getId();
            $title = $recording->getTitle();
            $ytLink = $recording->getRecordingLink();

            $ytLinkFormatted = $this->getYoutubeEmbedUrl($ytLink);

            $response = [
                'id' => $id,
                'title' => $title,
                'link' => $ytLinkFormatted
            ];
            return new JsonResponse($response, 201);
        } else {
            return new JsonResponse([], 500);
        }
    }

    /**
     * @Route("/recording/deleteRecording/{id}", name="recording/deleteRecording/{id}")
     */
    public function deleteRecordingAction(Request $request)
    {


    }
    private function getYoutubeEmbedUrl($url){

        $urlParts   = explode('/', $url);
        $vidid      = explode( '&', str_replace('watch?v=', '', end($urlParts) ) );

        return 'https://www.youtube.com/embed/' . $vidid[0] ;
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
//        $testYT = $this->getYoutubeEmbedUrl('https://www.youtube.com/watch?v=kHbQr6Xsy8U');
//        print_r($testYT);
//        exit();

        $this->getDoctrine()->getRepository(Recording::class)->findUserRecordings(1);
    }
}