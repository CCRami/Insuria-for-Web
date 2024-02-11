<?php

namespace App\Repository;

use App\Entity\Indemnisation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Indemnisation>
 *
 * @method Indemnisation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Indemnisation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Indemnisation[]    findAll()
 * @method Indemnisation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IndemnisationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Indemnisation::class);
    }

//    /**
//     * @return Indemnisation[] Returns an array of Indemnisation objects
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

//    public function findOneBySomeField($value): ?Indemnisation
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
