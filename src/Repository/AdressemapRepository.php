<?php

namespace App\Repository;

use App\Entity\Adressemap;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Adressemap>
 *
 * @method Adressemap|null find($id, $lockMode = null, $lockVersion = null)
 * @method Adressemap|null findOneBy(array $criteria, array $orderBy = null)
 * @method Adressemap[]    findAll()
 * @method Adressemap[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdressemapRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Adressemap::class);
    }

//    /**
//     * @return Adressemap[] Returns an array of Adressemap objects
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

//    public function findOneBySomeField($value): ?Adressemap
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
