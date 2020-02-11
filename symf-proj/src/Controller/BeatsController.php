<?php

namespace App\Controller;

use App\Entity\Beat;
use App\Repository\BeatRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BeatsController extends AbstractController
{
    /**
     * @Route("/beats", name="beatsList")
     * @return Response
     */
    public function index(): Response
    {
        /*** @var BeatRepository $beatsRepository */
        $beatsRepository = $this->getDoctrine()->getRepository(Beat::class);
        $beatsList = $beatsRepository->findAll();

        return $this->render('@TwigTemplate/beats/index.html.twig', [
            'beats' => $beatsList
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
