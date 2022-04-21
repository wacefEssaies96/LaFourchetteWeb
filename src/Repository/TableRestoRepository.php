<?php

namespace App\Repository;

use App\Entity\TableResto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TableResto|null find($id, $lockMode = null, $lockVersion = null)
 * @method TableResto|null findOneBy(array $criteria, array $orderBy = null)
 * @method TableResto[]    findAll()
 * @method TableResto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TableRestoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TableResto::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(TableResto $entity, bool $flush = true): void
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
    public function remove(TableResto $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function TD()
    {
        return
            $this->createQueryBuilder('t')
            ->select('t')
            ->where('t.etat = :value ')
            ->setParameter('value', 'Disponible')
            ->getQuery()
            ->getResult()
        ;
    }
    
    public function Searchnbrplace($nbrp)
    {
         return $this->createQueryBuilder('tr')
            ->select('tr')
            ->where('tr.nbrplace LIKE :nbrp')
            ->setParameter('nbrp','%'.$nbrp.'%')
            ->getQuery()->getResult();
    }
    public function tritableresto($type){

         
        $queryBuilder=$this->createQueryBuilder('tr')
            ->select('tr');
            if($type == 'nbrp'){
                $queryBuilder->orderBy('tr.nbrplace', 'ASC');
            }if($type == 'etat'){
                $queryBuilder->orderBy('tr.etat', 'ASC');
            }else{
                $queryBuilder->orderBy('tr.prix', 'ASC');
            }
            return $queryBuilder->getQuery()->getResult();
    }
    

    // /**
    //  * @return TableResto[] Returns an array of TableResto objects
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
    public function findOneBySomeField($value): ?TableResto
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
