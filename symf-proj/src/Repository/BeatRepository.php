<?php

namespace App\Repository;

use App\Entity\Beat;
use App\Entity\Category;
use App\Entity\Genre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Beat|null find($id, $lockMode = null, $lockVersion = null)
 * @method Beat|null findOneBy(array $criteria, array $orderBy = null)
 * @method Beat[]    findAll()
 * @method Beat[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BeatRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Beat::class);
    }

    /**
     * @return Beat[] Returns an array of Beat objects
     */
    public function findShortBeats(): array
    {
        return $this->createQueryBuilder('beat')
            ->where('beat.beatLength <= :shortBeatLength')
            ->setParameter('shortBeatLength', 1.5)
            ->getQuery()
            ->getResult();
    }

    public function findBeatsByCategory(Category $category)
    {
        return $this->createQueryBuilder('beat')
            ->where('beat.category = :category')
            ->setParameter('category', $category)
            ->getQuery()
            ->getResult();
    }

    public function findBeatsByGenre(Genre $genre)
    {
        return $this->createQueryBuilder('beat')
            ->where('beat.genre = :genre')
            ->setParameter('genre', $genre)
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Beat[] Returns an array of Beat objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Beat
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
