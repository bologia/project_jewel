<?php

namespace App\Repository;

use App\Entity\Comporte;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Comporte|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comporte|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comporte[]    findAll()
 * @method Comporte[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComporteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Comporte::class);
    }

    // /**
    //  * @return Comporte[] Returns an array of Comporte objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Comporte
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
