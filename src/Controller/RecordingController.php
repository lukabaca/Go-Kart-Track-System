<?php
namespace App\Controller;
use App\Entity\Recording;
use App\Form\RecordingType;
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

            $id = $recording->getId();
            $title = $recording->getTitle();
            $ytLink = $recording->getRecordingLink();
            $ytLinkFormatted = $this->getYoutubeEmbedUrl($ytLink);
            $response = [
                'id' => $id,
                'title' => $title,
                'link' => $ytLinkFormatted,
            ];
            return new JsonResponse($response, 201);
        } else {
            return new JsonResponse([], 500);
        }
    }

    /**
     * @Route("/recording/deleteRecording/{id}", name="recording/deleteRecording/{id}")
     */
    public function deleteRecordingAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $recording = $em->getRepository(Recording::class)->find($id);
        if(!$recording) {
            return new JsonResponse([], 404);
        }
        $recordingUserId = $recording->getUser()->getId();
        $loggedUserId = $this->getUser()->getId();
        if($recordingUserId != $loggedUserId) {
            return new JsonResponse(['cant delete someones recording'], 401);
        }
        $em->remove($recording);
        $em->flush();
        return new JsonResponse([], 200);
    }
    private function getYoutubeEmbedUrl($url){
        $urlParts   = explode('/', $url);
        $vidid      = explode( '&', str_replace('watch?v=', '', end($urlParts) ) );
        return 'https://www.youtube.com/embed/' . $vidid[0] ;
    }
}