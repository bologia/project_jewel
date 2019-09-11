<?php

namespace App\Controller;

use App\Entity\News;
use App\Entity\Marque;
use App\Entity\Produit;
use App\Entity\Categorie;
use App\Form\ProduitType;
use App\Entity\Appartient;
use App\Form\AppartientType;
use App\Repository\MarqueRepository;
use App\Repository\ProduitRepository;
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
        $marque = $repo->findAll();
        
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
    public function shop(ProduitRepository $repoproduit, CategorieRepository $repocategorie, MarqueRepository $repomarq) {

        $produit = $repoproduit->findAll();
        $categorie = $repocategorie->findAll();
        $marque = $repomarq->findAll();
        return $this->render('bijouterie/shop.html.twig', [
            'produits' => $produit,
            'categories' => $categorie,
            'marques' => $marque
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
