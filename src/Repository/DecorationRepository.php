<?php

namespace App\Repository;

use App\Entity\Decoration;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Decoration|null find($id, $lockMode = null, $lockVersion = null)
 * @method Decoration|null findOneBy(array $criteria, array $orderBy = null)
 * @method Decoration[]    findAll()
 * @method Decoration[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DecorationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Decoration::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Decoration $entity, bool $flush = true): void
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
    public function remove(Decoration $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
    public function SearchNomD($nom)
    {
         return $this->createQueryBuilder('d')
            ->select('d')
            ->where('d.nom LIKE :nom')
            ->setParameter('nom','%'.$nom.'%')
            ->getQuery()->getResult();
    }
    public function SearchPrixD($prix)
    {
         return $this->createQueryBuilder('d')
            ->select('d')
            ->where('d.prix LIKE :prix')
            ->setParameter('prix','%'.$prix.'%')
            ->getQuery()->getResult();
    }
    
    public function Search($TRD,$VRD)
    {
         $queryBuilder = $this->createQueryBuilder('d')
            ->select('d')
            ->setParameter('valeur','%'.$VRD.'%');
            if($TRD == 'prix'){
                $queryBuilder->where('d.prix LIKE :valeur');
            }else{
                $queryBuilder->where('d.nom LIKE :valeur');
            }
            return $queryBuilder->getQuery()->getResult();
    }
    public function tridecoration($type){

         
        $queryBuilder=$this->createQueryBuilder('d')
            ->select('d');
            if($type == 'nom'){
                $queryBuilder->orderBy('d.nom', 'ASC');
            }else{
                $queryBuilder->orderBy('d.prix', 'ASC');
            }
            return $queryBuilder->getQuery()->getResult();
    }
    
    // /**
    //  * @return Decoration[] Returns an array of Decoration objects
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
    public function findOneBySomeField($value): ?Decoration
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
