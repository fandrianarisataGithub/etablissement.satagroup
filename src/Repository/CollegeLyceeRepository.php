<?php

namespace App\Repository;

use App\Entity\CollegeLycee;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CollegeLycee|null find($id, $lockMode = null, $lockVersion = null)
 * @method CollegeLycee|null findOneBy(array $criteria, array $orderBy = null)
 * @method CollegeLycee[]    findAll()
 * @method CollegeLycee[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CollegeLyceeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CollegeLycee::class);
    }

    // /**
    //  * @return CollegeLycee[] Returns an array of CollegeLycee objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CollegeLycee
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
