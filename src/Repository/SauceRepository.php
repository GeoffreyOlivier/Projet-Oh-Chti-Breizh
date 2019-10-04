<?php

namespace App\Repository;

use App\Entity\Sauce;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Sauce|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sauce|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sauce[]    findAll()
 * @method Sauce[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SauceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Sauce::class);
    }

    // /**
    //  * @return Sauce[] Returns an array of Sauce objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sauce
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
