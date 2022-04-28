<?php

namespace App\Repository;

use App\Entity\Commentaire;
use App\Entity\Evenement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Evenement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Evenement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Evenement[]    findAll()
 * @method Evenement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EvenementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Evenement::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Evenement $entity, bool $flush = true): void
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
    public function remove(Evenement $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
      * @return Commentaire[] Returns an array of Evenement objects
     */

   public function Join()
    {   $em=$this->getEntityManager();
        $query = $em->createQueryBuilder()
            ->select('c')
            ->from('App\Entity\Commentaire', 'c')
            ->leftJoin('c.idevent', 'e', 'WITH', 'e.id=c.idevent')
            ->from('App\Entity\Evenement', 'e');
        return $query->getQuery()->getResult();
        return $query->getResult();


        
    }

    /*
    public function findOneBySomeField($value): ?Evenement
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function orderbydesignation(){
        $em=$this->getEntityManager();
        $query =$em->createQuery(' select e from App\Entity\Evenement e order by e.designatione ASC');
         return $query->getResult();
    }
    public function orderbyDate(){
        $em=$this->getEntityManager();
        $query =$em->createQuery(' select e from App\Entity\Evenement e order by e.datee ASC');
        return $query->getResult();
    }
    public function orderbyNbrPartcipants(){
        $em=$this->getEntityManager();
        $query =$em->createQuery(' select e from App\Entity\Evenement e order by e.nbrparticipants ASC');
        return $query->getResult();
    }

    public function searchEvent($te,$tt)
    {
        $queryBuilder=$this->createQueryBuilder('search')->select('search')
            ->setParameter('value', '%'.$tt.'%');
        if($te == 'nbrparticipants'){
            $queryBuilder->where('search.nbrparticipants LIKE :value');
        }else {
            $queryBuilder->where('search.designatione LIKE :value');
        }
        return $queryBuilder
            ->getQuery()
            ->getResult();
    }




}

