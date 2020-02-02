<?php

namespace App\Controller;

use App\Entity\Competition;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
     * @return Response
     */
    public function validateSubmittingBeat(Request $request): ?Response
    {
        /**
         * @var UploadedFile $file
         */
        $file = $request->files->get('file');

        $saveDestination = $this->getParameter('kernel.project_dir').'/public/upload';
        $originalFilename = $file->getClientOriginalName();
        $file->move($saveDestination, $originalFilename);

        return new JsonResponse(
            ['success' => true]
        );
    }
}