<?php

namespace App\Repository;

use App\Entity\Genre;
use App\Entity\Sample;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query\Expr\Join;

/**
 * @method Genre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Genre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Genre[]    findAll()
 * @method Genre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GenreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Genre::class);
    }

    public function getGenresWithCountOfBeats(): array
    {
        $q = $this->createQueryBuilder('g')
            ->select('count(s.id) AS count')
            ->addSelect('g.name')
            ->addSelect('g.id')
            ->leftJoin(Sample::class, 's', Join::WITH,
                's.category = g.id')
            ->orderBy('count', 'DESC')
            ->addOrderBy('g.name', 'ASC')
            ->groupBy('g.id')
            ->addGroupBy('g.name');

        return $q->getQuery()->getResult();
    }

    /**
     * @param string $name
     * @return Genre|null
     * @throws NonUniqueResultException
     */
    public function findGenreByName(string $name): ?Genre
    {
        return $this->createQueryBuilder('g')
            ->where('g.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }

    // /**
    //  * @return Genre[] Returns an array of Genre objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('g.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Genre
    {
        return $this->createQueryBuilder('g')
            ->andWhere('g.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
