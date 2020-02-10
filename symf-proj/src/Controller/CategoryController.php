<?php

namespace App\Controller;

use App\Entity\Beat;
use App\Entity\Category;
use App\Repository\BeatRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    /**
     * @Route("/categories", name="categoriesList")
     * @return Response
     */
    public function index(): Response
    {
        /**
         * @var CategoryRepository $categoriesRepository
         */
        $categoriesRepository = $this->getDoctrine()->getRepository(Category::class);
        $categoriesList = $categoriesRepository->getCategoriesWithCountOfBeats();

        return $this->render('@TwigTemplate/categories/index.html.twig', [
            'categories' => $categoriesList
        ]);
    }

    /**
     * @Route("/categories/{id}", name="categoryDetail", requirements={"id": "[0-9]+"})
     * @param Request $request
     * @return Response
     */
    public function show(Request $request): Response
    {
        /**
         * @var CategoryRepository $categoriesRepository
         */
        $categoriesRepository = $this->getDoctrine()->getRepository(Category::class);

        /**
         * @var BeatRepository $beatsRepository
         */
        $beatsRepository = $this->getDoctrine()->getRepository(Beat::class);

        /**
         * @var Category|null $category
         */
        $category = $categoriesRepository->find($request->get('id'));

        if (!$category) {
            throw $this->createNotFoundException();
        }

        $beatsByCategory = $beatsRepository->findBeatsByCategory($category);

        return $this->render('@TwigTemplate/categories/detail.html.twig', [
            'category' => $category,
            'beats' => $beatsByCategory
        ]);
    }
}
