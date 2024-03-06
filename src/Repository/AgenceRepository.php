<?php

namespace App\Repository;

use App\Entity\Agence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Agence>
 *
 * @method Agence|null find($id, $lockMode = null, $lockVersion = null)
 * @method Agence|null findOneBy(array $criteria, array $orderBy = null)
 * @method Agence[]    findAll()
 * @method Agence[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AgenceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Agence::class);
    }

//    /**
//     * @return Agence[] Returns an array of Agence objects
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

//    public function findOneBySomeField($value): ?Agence
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
function findavisbyagence($idag){
    
    $em=$this->getEntityManager();
    
    return 
    $em->createQuery('SELECT b from App\Entity\Avis b WHERE 
    b.agenceav=:id')
    ->setParameter('id',$idag)
    ->getResult();
}


function findavisbyclient($idag){
    
    $em=$this->getEntityManager();
    
    return 
    $em->createQuery('SELECT b from App\Entity\Avis b WHERE 
    b.avis=:id')
    ->setParameter('id',$idag)
    ->getResult();
}

 function findBySearchTerm($agence)
{
    return $this->createQueryBuilder('a')
        ->where('a.nom LIKE :searchTerm')
        ->setParameter('searchTerm', '%' . $agence . '%')
        ->getQuery()
        ->getResult();
}

function OrderbyMail(){
    $em=$this->getEntityManager();
    return $em->createQuery('SELECT b from App\Entity\Agence b ORDER BY b.email
    ASC ')
    ->getResult();
}
function OrderagBynom(){
    $em=$this->getEntityManager();
    return $em->createQuery('SELECT a from App\Entity\Agence a ORDER BY a.nomage
    ASC ')
    ->getResult();
}
}
