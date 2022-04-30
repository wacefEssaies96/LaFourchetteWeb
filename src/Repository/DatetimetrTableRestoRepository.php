<?php

namespace App\Repository;

use App\Entity\DatetimetrTableResto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DatetimetrTableResto|null find($id, $lockMode = null, $lockVersion = null)
 * @method DatetimetrTableResto|null findOneBy(array $criteria, array $orderBy = null)
 * @method DatetimetrTableResto[]    findAll()
 * @method DatetimetrTableResto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DatetimetrTableRestoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DatetimetrTableResto::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(DatetimetrTableResto $entity, bool $flush = true): void
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
    public function remove(DatetimetrTableResto $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function findTRDate($id)
    {
        return
            $this->createQueryBuilder('dtr')
            ->select('d')
            ->leftJoin('App\Entity\TableResto', 't', 'WITH', 't.idt = dtr.idt')
            ->leftJoin('App\Entity\Datetimetr', 'd', 'WITH', 'd.iddt = dtr.iddt')
            ->where('dtr.idt = :value ')
            ->setParameter('value', $id)
            ->getQuery()
            ->getResult()
        ;
    }
    
    public function SearchTR($TRDTR,$VRDTR,$id)
    {
        
            $queryBuilder = $this->createQueryBuilder('dtr')
            ->select('d')
            ->leftJoin('App\Entity\TableResto', 't', 'WITH', 't.idt = dtr.idt')
            ->leftJoin('App\Entity\Datetimetr', 'd', 'WITH', 'd.iddt = dtr.iddt')
            ->where('dtr.idt = :value ')
            ->setParameter('value', $id)
            ->setParameter('valeur','%'.$VRDTR.'%');
            if($TRDTR == 'date'){
                $queryBuilder->andwhere('d.date LIKE :valeur');
            }else{
                $queryBuilder->andwhere('d.etat LIKE :valeur');
            }
            return $queryBuilder->getQuery()->getResult();
        
    }
    
    
    
    public function tridateTR($type,$id){
         
        $queryBuilder=$this->createQueryBuilder('dtr')
            ->select('d')
            ->leftJoin('App\Entity\TableResto', 't', 'WITH', 't.idt = dtr.idt')
            ->leftJoin('App\Entity\Datetimetr', 'd', 'WITH', 'd.iddt = dtr.iddt')
            ->where('dtr.idt = :value ')
            ->setParameter('value', $id);
            if($type == 'date'){
                $queryBuilder->orderBy('d.date', 'ASC');
            }else{
                $queryBuilder->orderBy('d.etat', 'ASC');
            }
            return $queryBuilder->getQuery()->getResult();
    }
    
    public function TD(){
         
        $queryBuilder=$this->createQueryBuilder('dtr')
            ->select('t')
            ->leftJoin('App\Entity\TableResto', 't', 'WITH', 't.idt = dtr.idt')
            ->leftJoin('App\Entity\Datetimetr', 'd', 'WITH', 'd.iddt = dtr.iddt')
            ->where('d.etat = :value ')
            ->setParameter('value', 'Disponible');
            return $queryBuilder->getQuery()->getResult();
    }
    public function DTD($id){
         
        $queryBuilder=$this->createQueryBuilder('dtr')
            ->select('d')
            ->leftJoin('App\Entity\TableResto', 't', 'WITH', 't.idt = dtr.idt')
            ->leftJoin('App\Entity\Datetimetr', 'd', 'WITH', 'd.iddt = dtr.iddt')
            ->where('d.etat = :value ')
            ->andwhere('dtr.idt = :id ')
            ->setParameter('value', 'Disponible')
            ->setParameter('id', $id);
            return $queryBuilder->getQuery()->getResult();
    }
    // /**
    //  * @return DatetimetrTableResto[] Returns an array of DatetimetrTableResto objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DatetimetrTableResto
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
