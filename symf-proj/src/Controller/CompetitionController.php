<?php

namespace App\Controller;

use App\Entity\Competition;
use App\Service\CompetitionGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @Route("/competition/submit", name="competitionSubmit")
     * @param Request $request
     * @param CompetitionGenerator $competitionGenerator
     * @return Response
     */
    public function submitBeat(Request $request, CompetitionGenerator $competitionGenerator): ?Response
    {
        $success = $competitionGenerator->saveUserBeat($request);

        return new JsonResponse(
            ['success' => $success]
        );
    }

    /**
     * @Route("/competition/create", name="competitionCreate")
     * @param CompetitionGenerator $competitionGenerator
     * @return Response
     */
    public function createCompetition(CompetitionGenerator $competitionGenerator): ?Response
    {
        $isCompetitionCreated = $competitionGenerator->createCompetition();

        return new JsonResponse(
            ['success' => $isCompetitionCreated]
        );
    }
}