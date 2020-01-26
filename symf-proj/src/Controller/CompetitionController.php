<?php

namespace App\Controller;

use App\Entity\Beat;
use App\Model\CompetitionModel;
use App\Repository\BeatRepository;
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
        $competitionModel = new CompetitionModel();
        $beat = $competitionModel->getRandomBeat();

        return $this->render('@TwigTemplate/competition/index.html.twig', [
            'beat' => $beat
        ]);
    }
}