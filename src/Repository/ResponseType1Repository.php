<?php

namespace App\Repository;

use App\Entity\ResponseType1;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ResponseType1|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResponseType1|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResponseType1[]    findAll()
 * @method ResponseType1[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResponseType1Repository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResponseType1::class);
    }

    // /**
    //  * @return ResponseType1[] Returns an array of ResponseType1 objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ResponseType1
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
