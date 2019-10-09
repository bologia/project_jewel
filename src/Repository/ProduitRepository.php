<?php

namespace App\Repository;

use App\Entity\Produit;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    // /**
    //  * @return Produit[] Returns an array of Produit objects
    //  */
    
    public function findByFilterAll($categories = null, $marques = null, $materiaux = null) 
    {        
        $catOk = ($categories != null && sizeof($categories) > 0);
        $marqOk = ($marques != null && sizeof($marques) > 0);
        $matOk = ($materiaux != null && sizeof($materiaux) > 0);

        if($catOk && $marqOk && $matOk){
            return $this->createQueryBuilder('p')
                        ->join('p.categorieProduit','c')
                        ->join('p.marque','m')
                        ->join('p.materiel','ma')
                        ->andWhere("c.id IN(:cat)")
                        ->andWhere("m.id IN(:marq)")
                        ->andWhere("ma.id IN(:mat)")
                        ->andWhere("p.activeProduit = true")
                        ->setParameters([
                            'marq' => array_values($marques),
                            'cat' => array_values($categories),
                            'mat' => array_values($materiaux)
                        ])
                        ->getQuery()
                        ->getResult();
        } else if($catOk && $marqOk){
            return $this->createQueryBuilder('p')
                        ->join('p.categorieProduit','c')
                        ->join('p.marque','m')                        
                        ->andWhere("c.id IN(:cat)")
                        ->andWhere("m.id IN(:marq)")                        
                        ->andWhere("p.activeProduit = true")
                        ->setParameters([
                            'marq' => array_values($marques),
                            'cat' => array_values($categories)                            
                        ])
                        ->getQuery()
                        ->getResult();
        } else if($catOk && $matOk){
            return $this->createQueryBuilder('p')
                        ->join('p.categorieProduit','c')                        
                        ->join('p.materiel','ma')
                        ->andWhere("c.id IN(:cat)")                        
                        ->andWhere("ma.id IN(:mat)")
                        ->andWhere("p.activeProduit = true")
                        ->setParameters([                            
                            'cat' => array_values($categories),
                            'mat' => array_values($materiaux)
                        ])
                        ->getQuery()
                        ->getResult();
        } else if($marqOk && $matOk){
            return $this->createQueryBuilder('p')                        
                        ->join('p.marque','m')
                        ->join('p.materiel','ma')                        
                        ->andWhere("m.id IN(:marq)")
                        ->andWhere("ma.id IN(:mat)")
                        ->andWhere("p.activeProduit = true")
                        ->setParameters([
                            'marq' => array_values($marques),                            
                            'mat' => array_values($materiaux)
                        ])
                        ->getQuery()
                        ->getResult();
        } else if($catOk){
            return $this->createQueryBuilder('p')
                        ->join('p.categorieProduit','c')
                        ->andWhere("c.id IN(:cat)")
                        ->andWhere("p.activeProduit = true")
                        ->setParameter('cat' , array_values($categories))
                        ->getQuery()
                        ->getResult();
        } else if($marqOk){
            return $this->createQueryBuilder('p')
                        ->join('p.marque','m')
                        ->andWhere("m.id IN(:marq)")
                        ->andWhere("p.activeProduit = true")
                        ->setParameter('marq' , array_values($marques))
                        ->getQuery()
                        ->getResult();
        } else if($matOk){
            return $this->createQueryBuilder('p')
                        ->join('p.materiel','ma')
                        ->andWhere("ma.id IN(:mat)")
                        ->andWhere("p.activeProduit = true")
                        ->setParameter('mat' , array_values($materiaux))
                        ->getQuery()
                        ->getResult();
        } else { 
            return $this->createQueryBuilder('p')
                        ->andWhere("p.activeProduit = true")
                        ->getQuery()
                        ->getResult();
        }
    }

    public function findByFilterCount($categories = null, $marques = null, $materiaux = null, $limite, $start)
    {        
        $catOk = ($categories != null && sizeof($categories) > 0);
        $marqOk = ($marques != null && sizeof($marques) > 0);
        $matOk = ($materiaux != null && sizeof($materiaux) > 0);
        if($catOk && $marqOk && $matOk){
            return $this->createQueryBuilder('p')
                        ->join('p.categorieProduit','c')
                        ->join('p.marque','m')
                        ->join('p.materiel','ma')
                        ->andWhere("c.id IN(:cat)")
                        ->andWhere("m.id IN(:marq)")
                        ->andWhere("ma.id IN(:mat)")
                        ->andWhere("p.activeProduit = true")      // pour afficher les articles non "supprimé"
                        ->orderBy('p.id', 'DESC')                 // trier en décroissant
                        ->setFirstResult($start)                  // pour la pagination
                        ->setMaxResults($limite)                  // pour la pagination
                        ->setParameters([
                            'marq' => array_values($marques),
                            'cat' => array_values($categories),
                            'mat' => array_values($materiaux)
                        ])
                        ->getQuery()
                        ->getResult();
        } else if($catOk && $marqOk){
            return $this->createQueryBuilder('p')
                        ->join('p.categorieProduit','c')
                        ->join('p.marque','m')                        
                        ->andWhere("c.id IN(:cat)")
                        ->andWhere("m.id IN(:marq)")                        
                        ->andWhere("p.activeProduit = true")
                        ->orderBy('p.id', 'DESC')
                        ->setFirstResult($start)
                        ->setMaxResults($limite)
                        ->setParameters([
                            'marq' => array_values($marques),
                            'cat' => array_values($categories)                            
                        ])
                        ->getQuery()
                        ->getResult();
        } else if($catOk && $matOk){
            return $this->createQueryBuilder('p')
                        ->join('p.categorieProduit','c')                        
                        ->join('p.materiel','ma')
                        ->andWhere("c.id IN(:cat)")                        
                        ->andWhere("ma.id IN(:mat)")
                        ->andWhere("p.activeProduit = true")
                        ->orderBy('p.id', 'DESC')
                        ->setFirstResult($start)
                        ->setMaxResults($limite)
                        ->setParameters([                            
                            'cat' => array_values($categories),
                            'mat' => array_values($materiaux)
                        ])
                        ->getQuery()
                        ->getResult();
        } else if($marqOk && $matOk){
            return $this->createQueryBuilder('p')                        
                        ->join('p.marque','m')
                        ->join('p.materiel','ma')                        
                        ->andWhere("m.id IN(:marq)")
                        ->andWhere("ma.id IN(:mat)")
                        ->andWhere("p.activeProduit = true")
                        ->orderBy('p.id', 'DESC')
                        ->setFirstResult($start)
                        ->setMaxResults($limite)
                        ->setParameters([
                            'marq' => array_values($marques),                            
                            'mat' => array_values($materiaux)
                        ])
                        ->getQuery()
                        ->getResult();
        } else if($catOk){
            return $this->createQueryBuilder('p')
                        ->join('p.categorieProduit','c')
                        ->andWhere("c.id IN(:cat)")
                        ->andWhere("p.activeProduit = true")
                        ->orderBy('p.id', 'DESC')
                        ->setFirstResult($start)
                        ->setMaxResults($limite)
                        ->setParameter('cat' , array_values($categories))
                        ->getQuery()
                        ->getResult();
        } else if($marqOk){
            return $this->createQueryBuilder('p')
                        ->join('p.marque','m')
                        ->andWhere("m.id IN(:marq)")
                        ->andWhere("p.activeProduit = true")
                        ->orderBy('p.id', 'DESC')
                        ->setFirstResult($start)
                        ->setMaxResults($limite)
                        ->setParameter('marq' , array_values($marques))
                        ->getQuery()
                        ->getResult();
        } else if($matOk){
            return $this->createQueryBuilder('p')
                        ->join('p.materiel','ma')
                        ->andWhere("ma.id IN(:mat)")
                        ->andWhere("p.activeProduit = true")
                        ->orderBy('p.id', 'DESC')
                        ->setFirstResult($start)
                        ->setMaxResults($limite)
                        ->setParameter('mat' , array_values($materiaux))
                        ->getQuery()
                        ->getResult();
        } else { 
            return $this->createQueryBuilder('p')
                        ->andWhere("p.activeProduit = true")
                        ->orderBy('p.id', 'DESC')
                        ->setFirstResult($start)
                        ->setMaxResults($limite)
                        ->getQuery()
                        ->getResult();
        }
    }

    // public function findByFilter($categories)
    // {
    //     return $this->createQueryBuilder('p')                        // On selectionne tous les produits
    //             ->join('p.categorieProduit','c')                     // en reliant la table des catégories
    //             ->where("c.id IN(:cat)")                             // quand l'id de la catégorie est dans le tableau
    //             ->andWhere("p.activeProduit = true")                // que les produits actifs           
    //                 ->setParameter('cat', array_values($categories)) // :cat => le tableau des catégories qu'on a donné
    //                 ->getQuery()
    //                 ->getResult()
    //             ;
    // }
}
