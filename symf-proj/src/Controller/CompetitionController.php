<?php

namespace App\Controller;

use App\Repository\BeatRepository;
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

    private BeatRepository $beatRepository;

    /**
     * CompetitionController constructor.
     * @param LoggerInterface $logger
     * @param CompetitionRepository $competitionRepository
     */
    public function __construct(LoggerInterface $logger,
                                CompetitionRepository $competitionRepository,
                                BeatRepository $beatRepository)
    {
        $this->logger = $logger;
        $this->competitionRepository = $competitionRepository;
        $this->beatRepository = $beatRepository;
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
     * @Route("/competition/{id}", name="competition_detail", requirements={"id": "[0-9]+"})
     * @param Request $request
     * @return Response
     */
    public function detail(Request $request): Response
    {
        $competitionId = $request->get('id');
        $competition = $this->competitionRepository->findOneBy(['id' => $competitionId]);
        $beats = null;

        if ($competition) {
            $beats = $this->beatRepository->findBy(['competition' => $competition]);
        } else {
            $this->logger->error('Не найдено соревнование с id ' . $competitionId);
        }

        return $this->render('@TwigTemplate/competition/detail.html.twig', [
            'competition' => $competition,
            'beats' => $beats
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