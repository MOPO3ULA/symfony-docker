<?php

namespace App\Repository;

use App\Entity\CompetitionRating;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method CompetitionRating|null find($id, $lockMode = null, $lockVersion = null)
 * @method CompetitionRating|null findOneBy(array $criteria, array $orderBy = null)
 * @method CompetitionRating[]    findAll()
 * @method CompetitionRating[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompetitionRatingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CompetitionRating::class);
    }

    public function findEntityComments($entity)
    {
        return $this->createQueryBuilder('cr')
            ->select('cr.type')
            ->addSelect('count(cr.type) as count')
            ->where('cr.entity = :entity')
            ->setParameter('entity', $entity)
            ->groupBy('cr.type')
            ->indexBy('cr', 'cr.type')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return CompetitionRating[] Returns an array of CompetitionRating objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CompetitionRating
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
