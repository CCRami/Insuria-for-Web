<?php

namespace App\Repository;

use App\Entity\Avis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Avis>
 *
 * @method Avis|null find($id, $lockMode = null, $lockVersion = null)
 * @method Avis|null findOneBy(array $criteria, array $orderBy = null)
 * @method Avis[]    findAll()
 * @method Avis[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AvisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Avis::class);
    }

//    /**
//     * @return Avis[] Returns an array of Avis objects
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

//    public function findOneBySomeField($value): ?Avis
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
function findavisbyclient($Avis){
    
    $em=$this->getEntityManager();
    
    return 
    $em->createQuery('SELECT b from App\Entity\Avis b JOIN b.Avis z WHERE  
    z.id=:id')
    ->setParameter('id',$Avis)
    ->getResult();
}

function findavisbyagence($id){
    
    $em=$this->getEntityManager();
    
    return 
    $em->createQuery('SELECT b from App\Entity\Avis b JOIN b.agenceav z WHERE  
    z.id=:id')
    ->setParameter('id',$id)
    ->getResult();
}

function findavisbyid($id){
    
    $em=$this->getEntityManager();
    
    return 
    $em->createQuery('SELECT b from App\Entity\Avis b JOIN b.Avis z WHERE  
    z.id=:id')
    ->setParameter('id',$id)
    ->getResult();
}
function findAllbyetat(){
    
    $em=$this->getEntityManager();
    
    return 
    $em->createQuery('SELECT b from App\Entity\Avis b  WHERE  
    b.etat=true')
    
    ->getResult();
}


}
