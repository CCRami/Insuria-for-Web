<?php

namespace App\Repository;

use App\Entity\Assurance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Assurance>
 *
 * @method Assurance|null find($id, $lockMode = null, $lockVersion = null)
 * @method Assurance|null findOneBy(array $criteria, array $orderBy = null)
 * @method Assurance[]    findAll()
 * @method Assurance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssuranceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Assurance::class);
    }

//    /**
//     * @return Assurance[] Returns an array of Assurance objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Assurance
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
