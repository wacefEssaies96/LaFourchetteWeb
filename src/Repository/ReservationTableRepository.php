<?php

namespace App\Repository;

use App\Entity\ReservationTableResto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReservationTableResto|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReservationTableResto|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReservationTableResto[]    findAll()
 * @method ReservationTableResto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationTableRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReservationTableResto::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(ReservationTableResto $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(ReservationTableResto $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function JRT($value)
    {
        return
            $this->createQueryBuilder('tr')
            ->select('t')
            ->leftJoin('App\Entity\TableResto', 't', 'WITH', 't.idt = tr.idt')
            ->leftJoin('App\Entity\Reservation', 'r', 'WITH', 'r.idr = tr.idr')
            ->where('r.idr = :value ')
            ->setParameter('value', $value)
            ->getQuery()
            ->getResult()
        ;
    }
    // /**
    //  * @return ReservationTableResto[] Returns an array of ReservationTableResto objects
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
    public function findOneBySomeField($value): ?ReservationTableResto
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
