<?php

namespace App\Controller;

use App\Service\Rating\Rating;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RatingController extends AbstractController
{
    /**
     * @var Rating $rating
     */
    private Rating $rating;

    public function __construct(Rating $rating)
    {
        $this->rating = $rating;
    }

    /**
     * @Route("/rating/submit", name="ratingSubmit")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $isSaved = $this->rating->saveRating($request);

        if ($isSaved) {
            $this->rating->calculate();
        }

        return new JsonResponse(
            ['success' => $isSaved]
        );
    }
}
