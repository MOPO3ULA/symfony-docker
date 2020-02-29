<?php

namespace App\Controller;

use App\Repository\BeatRepository;
use App\Repository\CompetitionRepository;
use App\Service\CompetitionGenerator;
use App\Service\UserBeat;
use App\Service\UserCompetition;
use Knp\Component\Pager\PaginatorInterface;
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
     * @var BeatRepository
     */
    private BeatRepository $beatRepository;

    /**
     * @var PaginatorInterface
     */
    private PaginatorInterface $paginator;

    /**
     * @var UserCompetition $userCompetition
     */
    private UserCompetition $userCompetition;

    /**
     * CompetitionController constructor.
     * @param LoggerInterface $logger
     * @param CompetitionRepository $competitionRepository
     * @param BeatRepository $beatRepository
     * @param PaginatorInterface $paginator
     * @param UserCompetition $userCompetition
     */
    public function __construct(LoggerInterface $logger,
                                CompetitionRepository $competitionRepository,
                                BeatRepository $beatRepository,
                                PaginatorInterface $paginator,
                                UserCompetition $userCompetition)
    {
        $this->logger = $logger;
        $this->competitionRepository = $competitionRepository;
        $this->beatRepository = $beatRepository;
        $this->paginator = $paginator;
        $this->userCompetition = $userCompetition;
    }

    /**
     * @Route("/competition", name="competitionList")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $userCompetitionIds = $this->userCompetition->getUserCompetitionIds();
        $competitionsList = $this->competitionRepository->getFindAllQueryBuilder();

        $pagination = $this->paginator->paginate(
            $competitionsList,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('@TwigTemplate/competition/index.html.twig', [
            'pagination' => $pagination,
            'userCompetitionIds' => $userCompetitionIds
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

        if ($competition) {
            $isCompetitionFound = $this->userCompetition->getIsUserCompetitionFound($competitionId);
            $beats = $this->beatRepository->findBy(['competition' => $competition]);
            $beatsGroups = array_chunk($beats, 3, true);
        } else {
            $this->logger->error('Не найдено соревнование с id ' . $competitionId);
            throw $this->createNotFoundException('Не найдено соревнование с id ' . $competitionId);
        }

        return $this->render('@TwigTemplate/competition/detail.html.twig', [
            'competition' => $competition,
            'beats' => $beats,
            'beatsGrouped' => $beatsGroups,
            'isCompetitionFound' => $isCompetitionFound
        ]);
    }

    /**
     * @Route("/competition/submit", name="competitionSubmit")
     * @param Request $request
     * @param UserBeat $userBeat
     * @return Response
     */
    public function submitBeat(Request $request, UserBeat $userBeat): ?Response
    {
        $status = 'success';
        $errorMessage = '';

        $success = $userBeat->saveUserBeat($request);

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