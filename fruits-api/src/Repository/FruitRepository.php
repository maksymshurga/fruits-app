<?php

namespace App\Repository;

use App\Entity\Fruit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

/**
 * @extends ServiceEntityRepository<Fruit>
 *
 * @method Fruit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Fruit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Fruit[]    findAll()
 * @method Fruit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FruitRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Fruit::class);
    }

    public function add(Fruit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Fruit $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @return Fruit[] Returns an array of Fruit objects
     */
    public function findByNameAndFamily($name, $family, $page, $limit): array
    {
        $queryBuilder = $this->createQueryBuilder('f')
            ->where('f.name LIKE :name')
            ->setParameter('name', '%'.$name.'%')
            ->andWhere('f.family LIKE :family')
            ->setParameter('family', '%'.$family.'%')
            ->setFirstResult(($page - 1) * $limit)
            ->setMaxResults($limit);

        $query = $queryBuilder->getQuery();

        $query->setHydrationMode(Query::HYDRATE_ARRAY);

        $fruits = $query->execute();

        $totalCount = $this->createQueryBuilder('f')
            ->where('f.name LIKE :name')
            ->setParameter('name', '%'.$name.'%')
            ->andWhere('f.family LIKE :family')
            ->setParameter('family', '%'.$family.'%')
            ->select('COUNT(f)')
            ->getQuery()
            ->getSingleScalarResult();

        return [
            'fruits' => $fruits,
            'meta' => [
                'page' => $page,
                'limit' => $limit,
                'totalCount' => $totalCount,
                'totalPages' => ceil($totalCount / $limit)
            ]
        ];
    }

    /**
     * @return int Returns updated count
     */
    public function updateFavorite($id, $favorite): int
    {
        return $this->createQueryBuilder('f')
            ->update()
            ->set('f.favorite', ':favorite')
            ->where('f.id = :id')
            ->setParameter('id', $id)
            ->setParameter('favorite', $favorite)
            ->getQuery()
            ->execute();
    }

    /**
     * @return Fruit[] Returns an array of Fruit objects
     */
    public function findFavorites(): array
    {
        $query = $this->createQueryBuilder('f')
            ->where('f.favorite = 1')
            ->getQuery();
        $query->setHydrationMode(Query::HYDRATE_ARRAY);

        return $query->execute();
    }
}
