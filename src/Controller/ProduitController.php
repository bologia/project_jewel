<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Entity\Categorie;
use App\Form\ProduitType;
use App\Repository\CategorieRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProduitController extends AbstractController
{
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
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function deleteproduit(Produit $produit, Request $request)
    {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($produit);
            $manager->flush();

        return $this->redirectToRoute('shop');
    }
}
