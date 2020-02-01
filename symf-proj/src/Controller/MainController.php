<?php

namespace App\Controller;

use App\Model\CompetitionModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/main", name="main")
     */
    public function index(): Response
    {
        $competitionModel = new CompetitionModel();
        $competition = $competitionModel->getRandomBeat();

        $em = $this->getDoctrine()->getManager();
        $em->persist($competition);
        $em->flush();

        return $this->render('@TwigTemplate/main/index.html.twig', [
            'key' => '123456'
        ]);
    }
}
