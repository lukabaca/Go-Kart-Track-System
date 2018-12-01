<?php

namespace App\Controller;
use App\Entity\News;
use App\Form\NewsType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
class DashboardController extends Controller
{
    /**
     * @Route("/dashboard/index", name="dashboard/index")
     */
    public function indexAction(Request $request)
    {
        $news = $this->getDoctrine()->getRepository(News::class)->findAll();
        return $this->render('views/controllers/dashboard/index.html.twig', [
                'newsList' => $news,
            ]
        );
    }

    /**
     * @Route("/dashboard/addNews", name="/dashboard/addNews")
     * @IsGranted("ROLE_ADMIN")
     */
    public function addNewsAction(Request $request)
    {
        $filePath = null;
        $news = new News();
        $newsForm = $this->createForm(NewsType::class, $news);
        $newsForm->handleRequest($request);
        if ($newsForm->isSubmitted() && $newsForm->isValid()) {
            $file = $news->getFile();
            if($file) {
                try {
                    $fileName = $this->generateUniqueFileName() . '.' . $file->guessExtension();
                    $file->move($this->getParameter('newsImage_directory'), $fileName);
                    $news->setFile($fileName);
                } catch (FileException $e) {
                    return $this->render('views/alerts/500.html.twig' , []);
                }
            }
            $em = $this->getDoctrine()->getManager();
            $em->persist($news);
            $em->flush();
            return $this->redirectToRoute('dashboard/index');
        }
        return $this->render('views/controllers/dashboard/addNews.html.twig', [
                'newsForm' => $newsForm->createView(),
                'filePath' => $filePath,
            ]
        );
    }

    /**
     * @Route("/dashboard/deleteNews/{id}", name="/dashboard/deleteNews/{id}")
     * @IsGranted("ROLE_ADMIN")
     */
    public function deleteNewsAction(Request $request, $id)
    {
        $news = $this->getDoctrine()->getManager()->getRepository(News::class)->find($id);
        if (!$news) {
            return new JsonResponse([], 404);
        }
        $file = $news->getFile();
        if ($file) {
            try {
                $fileName =  $file;
                $fileSystem = new Filesystem();
                $fileSystem->remove($this->getParameter('newsImage_directory') . '/' . $fileName);
                $em = $this->getDoctrine()->getManager();
                $em->remove($news);
                $em->flush();
                return new JsonResponse([$news], 200);
            } catch (FileException $e) {
                return new JsonResponse(['couldnt delete file from directory'], 500);
            }
        } else {
            $em = $this->getDoctrine()->getManager();
            $em->remove($news);
            $em->flush();
            return new JsonResponse([$news], 200);
        }
    }

    /**
     * @return string
     */
    private function generateUniqueFileName()
    {
        return md5(uniqid());
    }
}