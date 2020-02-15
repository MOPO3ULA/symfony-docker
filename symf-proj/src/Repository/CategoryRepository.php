<?php

namespace App\Repository;

use App\Entity\Beat;
use App\Entity\Category;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\Query\Expr\Join;

/**
 * @method Category|null find($id, $lockMode = null, $lockVersion = null)
 * @method Category|null findOneBy(array $criteria, array $orderBy = null)
 * @method Category[]    findAll()
 * @method Category[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);
    }

    /**
     * Получаем нужные поля для вывода категорий с количеством битов в них
     * @return array
     */
    public function getCategoriesWithCountOfBeats(): array
    {
        $q = $this->createQueryBuilder('c')
            ->select('count(b.id) AS count')
            ->addSelect('c.name')
            ->addSelect('c.id')
            ->leftJoin(Beat::class, 'b', Join::WITH,
                'b.category = c.id')
            ->orderBy('c.name', 'ASC')
            ->groupBy('c.id')
            ->addGroupBy('c.name');

        return $q->getQuery()->getResult();
    }

    /**
     * @param string $name
     * @return Category|null
     * @throws NonUniqueResultException
     */
    public function findCategoryByName(string $name): ?Category
    {
        return $this->createQueryBuilder('c')
            ->where('c.name = :name')
            ->setParameter('name', $name)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
