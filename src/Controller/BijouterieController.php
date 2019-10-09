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
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
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
     * @Route("/shop", name="shop")
     */
    public function shop(ProduitRepository $repoproduit, Request $request, CategorieRepository $repocategorie, MarqueRepository $repomarq, MaterielRepository $repomat) {
        // Si on a pas fait d'AJAX, on part de 0
        // Sinon on part de la valeur qu'on a envoyé en AJAX (offset)
        if($request->request->get('start') == null){
            $start = 0; 
        } else {
            $start = $request->request->get('start');
        }
        $limite = 18;

        // Recuperation des produits suivant la limite et le point de départ (au début 20, 0)
        $produit = $repoproduit->findBy(['activeProduit' => true], ['id' => 'DESC'], $limite, $start);

        // Total d'article
        $total = count($repoproduit->findBy(['activeProduit' => true]));
        
        // Liste des catégories, marques et matériaux pour le filtre
        $categorie  = $repocategorie->findAll();
        $marque     = $repomarq->findBy([],['nomMarque' => 'asc']);
        $materiel   = $repomat->findAll();

        // Si on vient via l'AJAX
        if ($request->isXmlHttpRequest()){
            // On affiche les produits récupérés dans une page spécifique 
            $render = $this->render('bijouterie/_shop-content.html.twig', [
                // Liste des produits à afficher
                'produits'   => $produit
            ]);

            // Response envoyé à l'AJAX
            // Render : notre vue
            // Size   : le nombre de produits à afficher
            // Link   : le lien avec les GET (donc les filtres)
            $response = [
                'render' => $render->getContent(),
                'size'   => sizeof($produit),
                'limit'  => $limite,
                'link'   => $request->getUri()
            ];

            return new JsonResponse($response);
        }

        // Affichage des 20 premiers articles, le reste se fera via l'AJAX donc on ne passera par là
        return $this->render('bijouterie/shop.html.twig', [
            // Filtres
            'categories' => $categorie,
            'marques'    => $marque,
            'materiaux'  => $materiel,
            // Total produit
            'nombre'     => $total,
            // Limite et point de départ
            'limit'      => $limite,
            'start'      => $start,
            // Lien avec les GET (donc les filtres)
            'link'       => $request->getUri(),
            // Les produits à afficher
            'produits'   => $produit
        ]);
    }
    
    /**
     * @Route("/shop/filter", name="filter")
     */
    public function filter(ProduitRepository $repoproduit, Request $request, CategorieRepository $repcat, ProduitRepository $repo)
    {
        $repomarq = $this->getDoctrine()->getRepository(Marque::class);
        $repomat  = $this->getDoctrine()->getRepository(Materiel::class);
        
        // Liste des filtres
        $categorie = $repcat->findAll();
        $marque    = $repomarq->findBy([],['nomMarque' => 'asc']);
        $materiel  = $repomat->findAll();

        // Initialisation des filtres
        $categoriesId = [];                                     
        $categories   = [];                                       
        $marquesId    = [];                                        
        $marques      = [];
        $materiauxId  = [];                                        
        $materiaux    = [];

        // pour la page marques aller directement au shop filtré. "query" -> method get dans l'url alr qu'au dessus en request->request c'est en post
        if($request->query->get('id') != null){                    
            $marquesId[] = $request->query->get('id');             
        }
        
        // On prends dans le form voir si c'est filtré
        // On récupère ensuite l'id des catégories/marques/materiaux (cochés)
        if($request->query->get('filtrecat') != null){           
            $categoriesId = $request->query->get('filtrecat');  
        } 
        if($request->query->get('filtremarq') != null){       
            $marquesId = $request->query->get('filtremarq');
        }
        if($request->query->get('filtremat') != null){       
            $materiauxId = $request->query->get('filtremat');
        }

        // On récupère les catégories/marques/matériaux filtrés grâce à l'id
        for($i = 0 ; $i < sizeof($categoriesId) ; $i++){         
            $categories[] = $repcat->find($categoriesId[$i]);      
        }
        for($j = 0 ; $j < sizeof($marquesId) ; $j++){           
            $marques[] = $repomarq->find($marquesId[$j]);     
        }
        for($k = 0 ; $k < sizeof($materiauxId) ; $k++){           
            $materiaux[] = $repomat->find($materiauxId[$k]);    
        }

        // Si on a pas fait d'AJAX, on part de 0
        // Sinon on part de la valeur qu'on a envoyé en AJAX (offset)
        if($request->request->get('start') == null) {
            $start = 0; 
        } else {
            $start = $request->request->get('start');
        }

        $limite = 18;

        // Recuperation des produits suivant la limite, le point de départ (au début 20, 0)
        // Suivant aussi les catégories, marques et matériaux cochés
        $produit = $repo->findByFilterCount($categories, $marques, $materiaux, $limite, $start);
        
        // Total de produits
        $total = count($repo->findByFilterAll($categories, $marques, $materiaux));
        
        // Si on vient de l'AJAX
        if ($request->isXmlHttpRequest()){
            // Affichage des produits
            $render = $this->render('bijouterie/_shop-content.html.twig', [
                // Liste des produits
                'produits' => $produit
            ]);

            // Response envoyé à l'AJAX
            // Render : notre vue
            // Size   : le nombre de produits à afficher
            // Link   : le lien avec les GET (donc les filtres)
            $response = [
                'render' => $render->getContent(),
                'size'   => sizeof($produit),
                'limit'  => $limite,
                'link'   => $request->getUri()
            ];

            return new JsonResponse($response);
        }

        // Affichage des 20 premiers articles, le reste se fera via l'AJAX donc on ne passera par là
        return $this->render('bijouterie/shop.html.twig', [
            // Liste des filtres
            'categories' => $categorie,
            'marques' => $marque,
            'materiaux' => $materiel,
            // Liste des filtres qu'on recoche (pour laisser les box cochées)
            'lastCategories' => $categories,
            'lastMarques' => $marques,
            'lastMat' => $materiaux,
            // Total de produit + lien avec les GET (donc les filtres)
            'nombre' => $total,
            'link'  => $request->getUri(),
            // Limit et point de départ
            'limit' => $limite,
            'start' => $start,
            // Produits à afficher
            'produits' => $produit
        ]);;
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
