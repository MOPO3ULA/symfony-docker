<?php

namespace App\Controller;

use App\Entity\Beat;
use App\Entity\Category;
use App\Entity\Genre;
use App\Repository\BeatRepository;
use App\Repository\CategoryRepository;
use App\Repository\GenreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GenreController extends AbstractController
{
    /**
     * @Route("/genres", name="genresList")
     * @return Response
     */
    public function index(): Response
    {
        /**
         * @var $genresRepository GenreRepository
         */
        $genresRepository = $this->getDoctrine()->getRepository(Genre::class);
        $genresList = $genresRepository->getGenresWithCountOfBeats();

        return $this->render('@TwigTemplate/genres/index.html.twig', [
            'genres' => $genresList
        ]);
    }

    /**
     * @Route("/genres/{id}", name="genreDetail", requirements={"id": "[0-9]+"})
     * @param Request $request
     * @return Response
     */
    public function show(Request $request): Response
    {
        /**
         * @var $genresRepository GenreRepository
         */
        $genresRepository = $this->getDoctrine()->getRepository(Genre::class);

        /**
         * @var $beatsRepository BeatRepository
         */
        $beatsRepository = $this->getDoctrine()->getRepository(Beat::class);

        /**
         * @var $genre Genre
         */
        $genre = $genresRepository->find($request->get('id'));

        if (!$genre) {
            throw $this->createNotFoundException();
        }

        $beatsByGenre = $beatsRepository->findBeatsByGenre($genre);

        return $this->render('@TwigTemplate/genres/detail.html.twig', [
            'genre' => $genre,
            'beats' => $beatsByGenre
        ]);
    }
}
