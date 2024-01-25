<?php

namespace App\Repository;

use App\Entity\CustomerReviews;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CustomerReviews>
 *
 * @method CustomerReviews|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomerReviews|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomerReviews[]    findAll()
 * @method CustomerReviews[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerReviewsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomerReviews::class);
    }

//    /**
//     * @return CustomerReviews[] Returns an array of CustomerReviews objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CustomerReviews
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
