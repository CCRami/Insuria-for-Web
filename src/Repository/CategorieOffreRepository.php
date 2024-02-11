<?php

namespace App\Repository;

use App\Entity\CategorieOffre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CategorieOffre>
 *
 * @method CategorieOffre|null find($id, $lockMode = null, $lockVersion = null)
 * @method CategorieOffre|null findOneBy(array $criteria, array $orderBy = null)
 * @method CategorieOffre[]    findAll()
 * @method CategorieOffre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CategorieOffreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CategorieOffre::class);
    }

//    /**
//     * @return CategorieOffre[] Returns an array of CategorieOffre objects
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

//    public function findOneBySomeField($value): ?CategorieOffre
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
