<?php

namespace App\Service\Rating;

use App\Entity\CompetitionRating;
use App\Repository\CompetitionRepository;

class RatingFactory
{
    /**
     * @var CompetitionRepository $competitionRepository
     */
    private CompetitionRepository $competitionRepository;

    public function __construct(CompetitionRepository $competitionRepository)
    {
        $this->competitionRepository = $competitionRepository;
    }

    public function getRatingEntity(string $link)
    {
        if (stripos($link, $_SERVER['HTTP_ORIGIN']) !== false) {
            $link = str_replace($_SERVER['HTTP_ORIGIN'] . '/', '', $link);
            $arLink = explode('/', $link);
            $category = $arLink[0];
            $id = $arLink[1];

            switch ($category) {
                case 'competition':
                    $comp = $this->competitionRepository->findOneBy(['id' => $id]);

                    if ($comp) {
                        $entity = new CompetitionRating();
                        $entity->setEntity($comp);
                        return $entity;
                    }

                    return false;
                case 'beats':
//                    return new Beat();
                    break;
            }
        }

        return false;
    }
}