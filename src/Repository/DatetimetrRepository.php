<?php

namespace App\Repository;

use App\Entity\Datetimetr;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Datetimetr|null find($id, $lockMode = null, $lockVersion = null)
 * @method Datetimetr|null findOneBy(array $criteria, array $orderBy = null)
 * @method Datetimetr[]    findAll()
 * @method Datetimetr[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DatetimetrRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Datetimetr::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Datetimetr $entity, bool $flush = true): void
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
    public function remove(Datetimetr $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
    
    public function MaxId()
    {
        return
            $this->createQueryBuilder('d')
            ->select('MAX(d.iddt)')
            ->getQuery()
            ->getResult()
        ;
    }

    public function Search($TRDTR,$VRDTR)
    {
         $queryBuilder = $this->createQueryBuilder('d')
            ->select('d')
            ->setParameter('valeur','%'.$VRDTR.'%');
            if($TRDTR == 'date'){
                $queryBuilder->where('d.date LIKE :valeur');
            }else{
                $queryBuilder->where('d.etat LIKE :valeur');
            }
            return $queryBuilder->getQuery()->getResult();
    }
    
    public function tridate($type){
         
        $queryBuilder=$this->createQueryBuilder('d')
            ->select('d');
            if($type == 'date'){
                $queryBuilder->orderBy('d.date', 'ASC');
            }else{
                $queryBuilder->orderBy('d.etat', 'ASC');
            }
            return $queryBuilder->getQuery()->getResult();
    }
    // /**
    //  * @return Datetimetr[] Returns an array of Datetimetr objects
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
    public function findOneBySomeField($value): ?Datetimetr
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
