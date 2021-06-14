<?php

namespace App\Repository;

use App\Entity\Universite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Universite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Universite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Universite[]    findAll()
 * @method Universite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UniversiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Universite::class);
    }

    // /**
    //  * @return Universite[] Returns an array of Universite objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Universite
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
