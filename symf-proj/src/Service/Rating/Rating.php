<?php

namespace App\Service\Rating;

use App\Entity\CompetitionRating;
use App\Entity\User;
use App\Repository\CompetitionRatingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Throwable;

class Rating
{
    /**
     * @var User|UserInterface|null $user
     */
    private $user;

    /**
     * @var RatingFactory $ratingFactory
     */
    private RatingFactory $ratingFactory;

    /**
     * @var EntityManagerInterface $em
     */
    private EntityManagerInterface $em;

    /**
     * @var CompetitionRating $ratingEntity
     */
    private CompetitionRating $ratingEntity;

    /**
     * @var CompetitionRatingRepository $crRepository
     */
    private CompetitionRatingRepository $crRepository;

    public function __construct(Security $security, RatingFactory $ratingFactory, EntityManagerInterface $em,
                                CompetitionRatingRepository $crRepository)
    {
        $this->ratingFactory = $ratingFactory;
        $this->em = $em;
        $this->crRepository = $crRepository;

        $user = $security->getUser();
        if ($user) {
            $this->user = $user;
        }
    }

    public function saveRating(Request $request)
    {
        $link = $request->request->get('currentLink');
        $this->ratingEntity = $this->ratingFactory->getRatingEntity($link);

        $this->ratingEntity->setUser($this->user);
        $this->ratingEntity->setType($request->request->getBoolean('voteType'));

        try {
            $this->em->persist($this->ratingEntity);
            $this->em->flush();
        } catch (Throwable $exception) {
            return $exception->getMessage();
        }

        return true;
    }

    /**
     * @return bool|string
     */
    public function calculate()
    {
        $sumComments = 0;

        $entity = $this->ratingEntity->getEntity();

        if (!$entity) {
            return false;
        }

        $comments = $this->crRepository->findEntityComments($entity);

        foreach ($comments as $type => $comment) {
            $sumComments += $comment['count'];
        }

        $positiveComments = $comments[1]['count'];

        $rating = (string) round(($positiveComments * 100) / $sumComments);
        $entity->setRating($rating);

        try {
            $this->em->persist($entity);
            $this->em->flush();
        } catch (Throwable $exception) {
            return $exception->getMessage();
        }

        return true;
    }
}