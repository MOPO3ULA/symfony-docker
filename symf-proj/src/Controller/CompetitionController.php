<?php

namespace App\Controller;

use App\Repository\CompetitionRepository;
use App\Service\CompetitionGenerator;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompetitionController extends AbstractController
{

    /**
     * @var LoggerInterface
     */
    private LoggerInterface $logger;

    /**
     * @var CompetitionRepository
     */
    private CompetitionRepository $competitionRepository;

    /**
     * CompetitionController constructor.
     * @param LoggerInterface $logger
     * @param CompetitionRepository $competitionRepository
     */
    public function __construct(LoggerInterface $logger,
                                CompetitionRepository $competitionRepository)
    {
        $this->logger = $logger;
        $this->competitionRepository = $competitionRepository;
    }

    /**
     * @Route("/competition", name="competitionList")
     * @return Response
     */
    public function index(): Response
    {
        $competitionsList = $this->competitionRepository->findAll();

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
        $status = 'success';
        $errorMessage = '';

        $success = $competitionGenerator->saveUserBeat($request);

        if (is_string($success)) {
            $status = 'error';
            $errorMessage = $success;
        }

        return new JsonResponse(
            [
                'status' => $status,
                'error' => $errorMessage
            ]
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