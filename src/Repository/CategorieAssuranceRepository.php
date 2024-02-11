<?php

namespace App\Repository;

use App\Entity\CategorieAssurance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategorieAssurance>
 *
 * @method CategorieAssurance|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategorieAssurance|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategorieAssurance[]    findAll()
 * @method CategorieAssurance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieAssuranceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategorieAssurance::class);
    }

//    /**
//     * @return CategorieAssurance[] Returns an array of CategorieAssurance objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?CategorieAssurance
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
