<?php

namespace App\Repository;

use App\Entity\ProduitFournisseur;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProduitFournisseur|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProduitFournisseur|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProduitFournisseur[]    findAll()
 * @method ProduitFournisseur[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitFournisseurRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProduitFournisseur::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(ProduitFournisseur $entity, bool $flush = true): void
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
    public function remove(ProduitFournisseur $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @return ProduitFournisseur[] 
    */
    
    public function join()
    {
        $em = $this->getEntityManager();
        $query = $em->createQuery("SELECT pf,f FROM App\Entity\ProduitFournisseur pf
            join App\Entity\Fournisseur f with pf.idf = f.idf
            order by pf.idf"
        );   
        return $query->getResult();
    }
    

    /*
    public function findOneBySomeField($value): ?ProduitFournisseur
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
