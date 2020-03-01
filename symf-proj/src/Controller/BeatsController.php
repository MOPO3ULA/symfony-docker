<?php

namespace App\Controller;

use App\Entity\Beat;
use App\Repository\BeatRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BeatsController extends AbstractController
{
    /**
     * @var PaginatorInterface $paginator
     */
    private PaginatorInterface $paginator;

    /**
     * @var BeatRepository $beatRepository
     */
    private BeatRepository $beatRepository;

    public function __construct(PaginatorInterface $paginator, BeatRepository $beatRepository)
    {
        $this->paginator = $paginator;
        $this->beatRepository = $beatRepository;
    }

    /**
     * @Route("/beats", name="beatsList")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $beatsListQB = $this->beatRepository->getFindAllQueryBuilder();

        $pagination = $this->paginator->paginate(
            $beatsListQB,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('@TwigTemplate/beats/index.html.twig', [
            'beats' => $pagination
        ]);
    }

    /**
     * @Route("/beats/{id}", name="beatsDetail", requirements={"id": "[0-9]+"})
     * @param Request $request
     * @return Response
     */
    public function show(Request $request): Response
    {
        $beatsRepository = $this->getDoctrine()->getRepository(Beat::class);
        $beat = $beatsRepository->find($request->get('id'));

        if (!$beat) {
            throw $this->createNotFoundException();
        }

        return $this->render('@TwigTemplate/beats/detail.html.twig', [
            'beat' => $beat
        ]);
    }
}
