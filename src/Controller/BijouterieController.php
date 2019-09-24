<?php

namespace App\Controller;

use App\Entity\News;
use App\Entity\Marque;
use App\Entity\Produit;
use App\Entity\Materiel;
use App\Entity\Categorie;
use App\Form\ProduitType;
use App\Entity\Appartient;
use App\Form\AppartientType;
use App\Repository\MarqueRepository;
use App\Repository\ProduitRepository;
use App\Repository\MaterielRepository;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BijouterieController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home() {
        //$i = 'Gianni the best';
        return $this->render('bijouterie/home.html.twig', [
            'controller_name' => 'BijouterieController', //'test' => $i
        ]);
    }

    /**
     * @Route("/marques", name="marques")
     */
    public function marques() {
        $repo = $this->getDoctrine()->getRepository(Marque::class);
        $marque = $repo->findBy([],['nomMarque' => 'asc']);
        
        return $this->render('bijouterie/marques.html.twig', [
            'marque' => $marque
        ]);
    }

    /**
     * @Route("/services", name="services")
     */
    public function services() {
        return $this->render('bijouterie/services.html.twig');
    }

    /**
     * @Route("/shop/{page}", name="shop", requirements={"page": "\d+"})
     */
    public function shop($page = 1, ProduitRepository $repoproduit, CategorieRepository $repocategorie, MarqueRepository $repomarq, MaterielRepository $repomat) {

        $limite = 24;
        $start = $page * $limite - $limite;
        $produit = $repoproduit->findBy(['activeProduit' => true], ['id' => 'DESC'], $limite, $start);
        $total = count($repoproduit->findAll());
        $pages = ceil($total / $limite);

        $categorie = $repocategorie->findAll();
        $marque = $repomarq->findBy([],['nomMarque' => 'asc']);
        $materiel = $repomat->findAll();

        return $this->render('bijouterie/shop.html.twig', [
            'produits' => $produit,
            'categories' => $categorie,
            'marques' => $marque,
            'materiaux' => $materiel,
            'pages' => $pages,
            'page' => $page,
            'nombre' => $total
        ]);
    }
    
    /**
     * @Route("/shop/filter/{page}", name="filter", requirements={"page": "\d+"})
     */
    public function filter($page = 1, Request $request, CategorieRepository $repcat, ProduitRepository $repo)
    {
        $repomarq = $this->getDoctrine()->getRepository(Marque::class);
        $repomat = $this->getDoctrine()->getRepository(Materiel::class);
        
        $categorie = $repcat->findAll();
        $marque = $repomarq->findBy([],['nomMarque' => 'asc']);//findAll();
        $materiel = $repomat->findAll();

        $categoriesId = [];                                     // Tableau pour dire que c'est vide si il n'y a pas de filtres
        $categories = [];                                       // Futur tableau des categories
        $marquesId = [];                                        
        $marques = [];
        $materiauxId = [];                                        
        $materiaux = [];

        if($request->request->get('filtrecat') != null){           // on prend dans le form voir si c'est filtré
            $categoriesId = $request->request->get('filtrecat');   // on récupère l'id des catégories (id coché)
        } 
        if($request->request->get('filtremarq') != null){       
            $marquesId = $request->request->get('filtremarq');
        }
        if($request->request->get('filtremat') != null){       
            $materiauxId = $request->request->get('filtremat');
        }
        for($i = 0 ; $i < sizeof($categoriesId) ; $i++){           // on parcours le tableau jusqu'au dernier id
            $categories[] = $repcat->find($categoriesId[$i]);      // puis on récupère la ligne entière de la cat suivant l'id
        }
        for($j = 0 ; $j < sizeof($marquesId) ; $j++){           
            // if($marquesId[$j] != -1){                           // Si on a cliqué sur "tout"
                $marques[] = $repomarq->find($marquesId[$j]);  
            // }   
        }
        for($k = 0 ; $k < sizeof($materiauxId) ; $k++){           
                $materiaux[] = $repomat->find($materiauxId[$k]);    
        }
        // if(sizeof($categories) > 0 && sizeof($marques) > 0){         // c'est pour les 2 en meme tps
        //     $produit = $repo->findByFilter3($categories, $marques);  // la fonction 3 dans le produitRepo
        // }
        // else if(sizeof($categories) > 0){                       // permet de ne pas être vide si rien est coché
        //     $produit = $repo->findByFilter($categories);        // on récupère les produits suivant les catégories du filtre (ProduitRepository)
        // }
        // else if(sizeof($marques) > 0){
        //     $produit = $repo->findByFiltermarq($marques);
        // } else {
        //     $produit = $repo->findBy(['activeProduit' => true]);     // on recupère tout(les produits activé) sinon
        // }

        $limite = 10;
        $start = $page * $limite - $limite;
        $produit = $repo->findByFilterAll($categories, $marques, $materiaux, $limite, $start); // fonction dans le repo
        $total = count($repo->findByFilterCount($categories, $marques, $materiaux));
        $pages = ceil($total / $limite);

        return $this->render('bijouterie/shop.html.twig', [
            'produits' => $produit,
            'categories' => $categorie,
            'lastCategories' => $categories, // Pour laisser afficher les checked
            'marques' => $marque,
            'lastMarques' => $marques,
            'materiaux' => $materiel,
            'lastMat' => $materiaux,
            'pages' => $pages,
            'page' => $page,
            'nombre' => $total
        ]);
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact() {
        return $this->render('bijouterie/contact.html.twig');
    }

    /**
     * @Route("/slider", name="slider")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function slider() {
        $repo = $this->getDoctrine()->getRepository(News::class);

        $newss = $repo->findAll();
        return $this->render('bijouterie/slider.html.twig', [
            'newss' => $newss
        ]);
    }
}
