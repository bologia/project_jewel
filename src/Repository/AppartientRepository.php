<?php

namespace App\Repository;

use App\Entity\Appartient;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Appartient|null find($id, $lockMode = null, $lockVersion = null)
 * @method Appartient|null findOneBy(array $criteria, array $orderBy = null)
 * @method Appartient[]    findAll()
 * @method Appartient[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppartientRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Appartient::class);
    }

    // /**
    //  * @return Appartient[] Returns an array of Appartient objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Appartient
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
