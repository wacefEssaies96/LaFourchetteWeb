<?php

namespace App\Repository;

use App\Entity\Employer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Data\SearchData;
use App\Form\SearchForm;
use Knp\Component\Pager\PaginatorInterface;
/**
 * @method Employer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Employer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Employer[]    findAll()
 * @method Employer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EmployerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Employer::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Employer $entity, bool $flush = true): void
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
    public function remove(Employer $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Employer[] Returns an array of Employer objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Employer
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    public function searchEmployer($ud,$uv)
    {
        $queryBuilder=$this->createQueryBuilder('search')->select('search')
            ->setParameter('value', '%'.$uv.'%');
            if($ud == 'nomPrenom'){
                $queryBuilder->where('search.nomPrenom LIKE :value');
            }else {
                $queryBuilder->where('search.idem LIKE :value');
            }
            return $queryBuilder
            ->getQuery()
            ->getResult();
    }
    public function triEmployer($type)
    {
        $queryBuilder=$this->createQueryBuilder('tri')->select('tri');
        if ($type == 'nomPrenom'){
            $queryBuilder->orderBy('tri.nomPrenom', 'ASC');
        }else {
            $queryBuilder->orderBy('tri.idem', 'ASC');
        }
          return  $queryBuilder->getQuery()->getResult();
    }
}
