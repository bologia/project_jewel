<?php

namespace App\Repository;

use App\Entity\Panier;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Panier|null find($id, $lockMode = null, $lockVersion = null)
 * @method Panier|null findOneBy(array $criteria, array $orderBy = null)
 * @method Panier[]    findAll()
 * @method Panier[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PanierRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Panier::class);
    }

    // /**
    //  * @return Panier[] Returns an array of Panier objects
    //  */
    
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
    
    public function findOneByUser($user)
    {
        return $this->createQueryBuilder('p')       //panier
            ->andWhere('p.user = :user')            //on récupère le panier du user qu'on lui envoie ($user)
            ->andWhere('p.datePanier is null')      //et si la date est null (pas encore validé)
            ->setParameter('user', $user)           //on passe les paramètres
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function findValidByUser($user)
    {
        return $this->createQueryBuilder('p') //panier
            ->andWhere('p.user = :user') //on récupère le panier du user qu'on lui envoie ($user)
            ->andWhere('p.datePanier is not null') //et si la date n'est pas null cette fois (panier validé)
            ->orderBy('p.datePanier', 'DESC')
            ->setParameter('user', $user) //tj pareil lol
            ->getQuery()
            ->getResult()
        ;
    }

    public function findAllValid($limite, $start)
    {
        return $this->createQueryBuilder('p')        //panier
            ->andWhere('p.datePanier is not null')   //on récupère tout les paniers validés par tout les users
            ->orderBy('p.datePanier', 'DESC')
            ->setFirstResult($start)
            ->setMaxResults($limite)
            ->getQuery()
            ->getResult()
        ;
    }

}
