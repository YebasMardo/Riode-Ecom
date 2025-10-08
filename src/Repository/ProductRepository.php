<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function productJoinCategory()
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.category', 'c')
            ->select('p', 'c')
            ->getQuery()
            ->getResult();;
    }

    public function findProductWithCount(): array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.category', 'c')
            ->addSelect('c')
            ->addSelect('COUNT(p.id) AS HIDDEN productCount') 
            ->groupBy('p.id')
            ->getQuery()
            ->getResult();
    }

    public function findAllWithImages(): array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.productImages', 'i')
            ->addSelect('i')
            ->getQuery()
            ->getResult();
    }

    //    /**
    //     * @return Product[] Returns an array of Product objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('p.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Product
    //    {
    //        return $this->createQueryBuilder('p')
    //            ->andWhere('p.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
