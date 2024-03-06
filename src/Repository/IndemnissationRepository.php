<?php

namespace App\Repository;

use App\Entity\Indemnissation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Indemnissation>
 *
 * @method Indemnissation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Indemnissation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Indemnissation[]    findAll()
 * @method Indemnissation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IndemnissationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Indemnissation::class);
    }

//    /**
//     * @return Indemnissation[] Returns an array of Indemnissation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Indemnissation
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
