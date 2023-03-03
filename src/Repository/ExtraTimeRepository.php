<?php

namespace App\Repository;

use App\Entity\ExtraTime;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ExtraTime|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExtraTime|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExtraTime[]    findAll()
 * @method ExtraTime[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExtraTimeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExtraTime::class);
    }

    // /**
    //  * @return ExtraTime[] Returns an array of ExtraTime objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ExtraTime
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
