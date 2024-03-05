<?php

namespace App\Repository;

use App\Entity\Sinistre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sinistre>
 *
 * @method Sinistre|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sinistre|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sinistre[]    findAll()
 * @method Sinistre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SinistreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sinistre::class);
    }

    public function findAllSins()
    {
        return $this->createQueryBuilder('u')
            ->orderBy('u.id', 'DESC')
            ->getQuery()
            ->getArrayResult();
    }

    public function findSinById($id)
    {
        return $this->createQueryBuilder('u')
            ->where('u.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

public function findEntitiesByString($username)
    {
    return $this->createQueryBuilder('u')
        ->where('u.sin_name LIKE :username')
        ->setParameter('username', '%' . $username . '%')
        ->getQuery()
        ->getResult();
    }


//    /**
//     * @return Sinistre[] Returns an array of Sinistre objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Sinistre
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
