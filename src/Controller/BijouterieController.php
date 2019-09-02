<?php

namespace App\Controller;

use App\Entity\News;
use App\Entity\Marque;
use App\Entity\Produit;
use App\Entity\Categorie;
use App\Form\ProduitType;
use App\Entity\Appartient;
use App\Form\AppartientType;
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
     * @Route("/actu", name="actu")
     */
    public function actu() {
        $repo = $this->getDoctrine()->getRepository(News::class);

        $newss = $repo->findAll();
        /*
        $repo->find(1) : news id 1
        $repo->findBy() : critere
        $repo->findOneBy() : idem mais un seul
        */
        return $this->render('bijouterie/actu.html.twig', [
            'newss' => $newss
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
     * @Route("/ajoutnews", name="ajoutnews")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function ajoutnews(Request $request, ObjectManager $manager) {
        $news = new News();

        $formnews = $this->createFormBuilder($news)
                         ->add('titreNews')
                         ->add('textNews')
                         ->add('imagNews', FileType::class)
                         ->getForm();

        $formnews->handleRequest($request);

        if($formnews->isSubmitted() && $formnews->isValid()) {
            $news->setCreatedAt(new \DateTime('now', new \DateTimeZone("Europe/Paris")));

            $imagee = $formnews->get('imagNews')->getData();
                if($imagee != NULL) {
                    $namee = $news->getId().''.mt_rand(0,1000000);
                    $imageNamee = $namee.'.'. $imagee->guessExtension();
                    $imagee->move(
                        $this->getParameter('image_directory'),
                        $imageNamee
                    );
                    $news->setImagNews($imageNamee);
                } else {
                    $news->setImagNews('imag/marseille.jpg');
                }

            $manager->persist($news);
            $manager->flush();

            return $this->redirectToRoute('actu');
        }

        return $this->render('bijouterie/ajoutnews.html.twig', [
            'formnews' => $formnews->createView()
        ]);
    }

    /**
     * @Route("/{id}/modifnews", name="modifnews")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function modifnews(News $news, Request $request, ObjectManager $manager) {

        $formnews = $this->createFormBuilder($news)
                         ->add('titreNews')
                         ->add('textNews')
                         ->getForm();

        $formnews->handleRequest($request);

        if($formnews->isSubmitted() && $formnews->isValid()) {

            $manager->persist($news);
            $manager->flush();

            return $this->redirectToRoute('actu');
        }

        return $this->render('bijouterie/modifnews.html.twig', [
            'formnews' => $formnews->createView()
        ]);
    }

    /**
     * @Route("/{id}/deletenews", name="deletenews")
     */
    public function deletenews(News $news, Request $request)
    {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($news);
            $manager->flush();



        return $this->redirectToRoute('actu');
    }

    /**
     * @Route("/shop", name="shop")
     */
    public function shop(ProduitRepository $repoproduit, CategorieRepository $repocategorie) {

        $produit = $repoproduit->findAll();
        $categorie = $repocategorie->findAll();
        return $this->render('bijouterie/shop.html.twig', [
            'produits' => $produit,
            'categories' => $categorie
        ]);
    }

    /**
     * @Route("/panier", name="panier")
     */
    public function panier() {
        return $this->render('bijouterie/panier.html.twig');
    }

    /**
     * @Route("/contact", name="contact")
     */
    public function contact() {
        return $this->render('bijouterie/contact.html.twig');
    }

    /**
     * @Route("/ajoutproduit", name="ajoutproduit")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function ajoutproduit(Request $request, ObjectManager $manager, CategorieRepository $repocategorie) {

        $produit = new Produit();
        $formproduit = $this->createForm(ProduitType::class, $produit);
        $formproduit->handleRequest($request);

        if($formproduit->isSubmitted() && $formproduit->isValid()) {
            $data = $request->request->get('produit');//nom du formulaire sans le Type lol
            foreach($data['Categorie'] as $cat_id) {
                // find($id) -> recupere un truc suivant l'id seulement
                // findAll() -> recupere tout
                // findBy() -> recupere tout selon un critère -> findBy(['nomCategorie'=>"Bijoux"])
                // findOneBy() -> comme au dessus, mais recupère qu'un seul
                $category = $repocategorie->find($cat_id);
                $produit->addCategorieProduit($category);
            }

            $image = $formproduit->get('imagProduit')->getData();
                if($image != NULL) {
                    $name = $produit->getId().''.mt_rand(0,1000000);
                    $imageName = $name.'.'. $image->guessExtension();
                    $image->move(
                        $this->getParameter('image_directory'),
                        $imageName
                    );
                    $produit->setImagProduit($imageName);
                } else {
                    $produit->setImagProduit('marseille.jpg');
                }
                
            $manager->persist($produit);
            $manager->flush();

            return $this->redirectToRoute('shop');
        }
        
        return $this->render('bijouterie/ajoutproduit.html.twig', [
            'formproduit' => $formproduit->createView()
        ]);
    }

    /**
     * @Route("/{id}/modifproduit", name="modifproduit")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function modifproduit(Produit $produit, Request $request, ObjectManager $manager) {

        $formproduit = $this->createFormBuilder($produit)
                         ->add('nomProduit')
                         ->add('prixProduit')
                         ->add('Categorie', EntityType::class, [
                            'class'         => Categorie::class,
                            'choice_label'  => 'nomCategorie',
                            'label'         => 'Catégorie(s) produit',
                            'multiple'      => true,
                            'mapped'        => false
                        ])
                         ->add('descriptionProduit')
                         ->getForm();

        $formproduit->handleRequest($request);

        if($formproduit->isSubmitted() && $formproduit->isValid()) {
                
            $manager->persist($produit);
            $manager->flush();

            return $this->redirectToRoute('shop');
        }

        return $this->render('bijouterie/modifproduit.html.twig', [
            'formproduit' => $formproduit->createView()
        ]);
    }

    /**
     * @Route("/{id}/deleteproduit", name="deleteproduit")
     */
    public function deleteproduit(Produit $produit, Request $request)
    {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($produit);
            $manager->flush();

        return $this->redirectToRoute('shop');
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
