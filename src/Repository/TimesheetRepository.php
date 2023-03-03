<?php

namespace App\Repository;

use App\Entity\Timesheet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Timesheet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Timesheet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Timesheet[]    findAll()
 * @method Timesheet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TimesheetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Timesheet::class);
    }

    /**
     * @param $employee
     * @return QueryBuilder (La dernière feuille de présences par salarié)
     *
     * @throws NonUniqueResultException
     */
    public function findOneByEmployeeSortedByDate($employee){
        return $this->createQueryBuilder('t')
            ->andWhere('t.employee = :emp')
            ->setParameter('emp', $employee)
            ->orderBy('t.created_at', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @param $employee
     * @return QueryBuilder (Les 6 dernières feuilles de présences par salarié)
     *
     */
    public function findlast6ByEmployeeSortedByDate($employee)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.employee = :emp')
            ->setParameter('emp', $employee)
            ->orderBy('t.created_at', 'DESC')
            ->setMaxResults(6)
            ->getQuery()
            ->getResult();
    }
    // /**
    //  * @return Timesheet[] Returns an array of Timesheet objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Timesheet
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
