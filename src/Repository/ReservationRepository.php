<?php

namespace App\Repository;

use App\Entity\Reservation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Reservation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Reservation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Reservation[]    findAll()
 * @method Reservation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReservationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Reservation::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Reservation $entity, bool $flush = true): void
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
    public function remove(Reservation $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
    public function Join(){
        $em = $this->getEntityManager();
        $query= $em->createQuery('select r,d from App\Entity\Reservation r Join App\Entity\DecorationReservation dr   Join App\Entity\Decoration d ');
        return $query->getResult();
    }
    public function MR($idU)
    {
        return
            $this->createQueryBuilder('r')
            ->select('r')
            ->where('r.idu = :value ')
            ->setParameter('value', $idU)
            ->getQuery()
            ->getResult()
        ;
    }
    public function MaxId()
    {
        return
            $this->createQueryBuilder('r')
            ->select('MAX(r.idr)')
            ->getQuery()
            ->getResult()
        ;
    }
    public function SearchUser($nom)
    {
         return $this->createQueryBuilder('r')
            ->select('r')
            ->leftJoin('App\Entity\Utilisateur', 'u', 'WITH', 'u.idu = r.idu')
            ->where('u.nomPrenom LIKE :nom')
            ->setParameter('nom','%'.$nom.'%')
            ->getQuery()->getResult();
    }
    public function Search($TRR,$VRR)
    {
         $queryBuilder = $this->createQueryBuilder('r')
            ->select('r')
            ->setParameter('valeur','%'.$VRR.'%');
            if($TRR == 'datecreation'){
                $queryBuilder->where('r.datecreation LIKE :valeur');
            }else if($TRR == 'datemodification'){
                $queryBuilder->where('r.datemodification LIKE :valeur');
            }else{
                $queryBuilder
                ->leftJoin('App\Entity\Utilisateur', 'u', 'WITH', 'u.idu = r.idu')
                ->where('u.nomPrenom LIKE :valeur');
            }
            return $queryBuilder->getQuery()->getResult();
    }
    public function SearchMesRes($idu,$TRMR,$VRR)
    {
        $queryBuilder = $this->createQueryBuilder('r')
        ->select('r')
        ->where('r.idu = :idu ')
        ;
        if($TRMR == 'datecreation'){
            $queryBuilder->andwhere('r.datecreation LIKE :valeur');
        }else{
            $queryBuilder->andwhere('r.datemodification LIKE :valeur');
        }
        return $queryBuilder
        ->setParameter('idu',$idu)
        ->setParameter('valeur','%'.$VRR.'%')
        ->getQuery()->getResult();
    }
    public function trireservation($type){

         
        $queryBuilder=$this->createQueryBuilder('r')
            ->select('r')
            ->leftJoin('App\Entity\Utilisateur', 'u', 'WITH', 'u.idu = r.idu');
            if($type == 'datecreation'){
                $queryBuilder->orderBy('r.datecreation', 'ASC');
            }if($type == 'datemodification'){
                $queryBuilder->orderBy('r.datemodification', 'ASC');
            }else{
                $queryBuilder->orderBy('r.idu', 'ASC');
            }
            return $queryBuilder->getQuery()->getResult();
    }
    
    public function triMesreservation($type,$idu){

         
        $queryBuilder=$this->createQueryBuilder('r')
            ->select('r')
            ->leftJoin('App\Entity\Utilisateur', 'u', 'WITH', 'u.idu = r.idu')
            ->where('u.idu LIKE :idu')
            ->setParameter('idu','%'.$idu.'%');
            if($type == 'datecreation'){
                $queryBuilder->orderBy('r.datecreation', 'ASC');
            }if($type == 'datemodification'){
                $queryBuilder->orderBy('r.datemodification', 'ASC');
            }else{
                $queryBuilder->orderBy('r.idu', 'ASC');
            }
            return $queryBuilder->getQuery()->getResult();
    }
    
    

    // /**
    //  * @return Reservation[] Returns an array of Reservation objects
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
    public function findOneBySomeField($value): ?Reservation
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
