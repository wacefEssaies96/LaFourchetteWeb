<?php

namespace App\Repository;

use App\Entity\Commentaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Commentaire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commentaire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commentaire[]    findAll()
 * @method Commentaire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commentaire::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Commentaire $entity, bool $flush = true): void
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
    public function remove(Commentaire $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
    public function orderbyNbrLike($id){


        return $this->createQueryBuilder('c')->where('c.idevent = :id')
            ->setParameter('id',$id)->orderBy('c.nbrlike' ,'ASC') ->getQuery()->getResult();
    }

    public function orderbycommentaire(){
        $em=$this->getEntityManager();
        $query =$em->createQuery(' select e from App\Entity\Commentaire e order by e.commantaire ASC');
        return $query->getResult();
    }

    public function orderbylikeback(){
        $em=$this->getEntityManager();
        $query =$em->createQuery(' select e from App\Entity\Commentaire e order by e.nbrlike DESC');
        return $query->getResult();
    }

    public function searchComment($te,$tt)
    {
        $queryBuilder=$this->createQueryBuilder('search')->select('search')
            ->setParameter('value', '%'.$tt.'%');
        if($te == 'nbrlike'){
            $queryBuilder->where('search.nbrlike LIKE :value');
        }else {
            $queryBuilder->where('search.commantaire LIKE :value');
        }
        return $queryBuilder
            ->getQuery()
            ->getResult();
    }
}
