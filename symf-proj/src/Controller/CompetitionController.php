<?php

namespace App\Controller;

use App\Entity\Competition;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompetitionController extends AbstractController
{
    /**
     * @Route("/competition", name="competitionList")
     * @return Response
     */
    public function index(): Response
    {
        $competitionRepository = $this->getDoctrine()->getRepository(Competition::class);
        $competitionsList = $competitionRepository->findAll();

        return $this->render('@TwigTemplate/competition/index.html.twig', [
            'competitions' => $competitionsList
        ]);
    }
}