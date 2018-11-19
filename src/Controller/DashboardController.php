<?php
/**
 * Created by PhpStorm.
 * User: Luka
 * Date: 2018-11-02
 * Time: 16:52
 */

namespace App\Controller;

use App\Entity\News;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
class DashboardController extends Controller
{
    /**
     * @Route("/dashboard/index", name="dashboard/index")
     */
    public function indexAction(Request $request)
    {
        $news = $this->getDoctrine()->getRepository(News::class)->findAll();
        return $this->render('views/controllers/dashboard/index.html.twig', [
                'news' => $news,
            ]
        );
    }
}