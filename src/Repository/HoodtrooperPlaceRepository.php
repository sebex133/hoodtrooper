<?php

namespace App\Repository;

use App\Entity\HoodtrooperPlace;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HoodtrooperPlace|null find($id, $lockMode = null, $lockVersion = null)
 * @method HoodtrooperPlace|null findOneBy(array $criteria, array $orderBy = null)
 * @method HoodtrooperPlace[]    findAll()
 * @method HoodtrooperPlace[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HoodtrooperPlaceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HoodtrooperPlace::class);
    }

    // /**
    //  * @return HoodtrooperPlace[] Returns an array of HoodtrooperPlace objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HoodtrooperPlace
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
