<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Panier;
use App\Entity\Produit;
use App\Entity\Comporte;
use App\Form\ProfilType;
use App\Repository\PanierRepository;
use App\Repository\ProduitRepository;
use App\Repository\ComporteRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class PanierController extends AbstractController
{
    /**
     * @Route("/panier", name="panier")
     * @Security("is_granted('ROLE_USER')")
     */
    public function panier(PanierRepository $repopan)
    {
        $user = $this->getUser();
        $panier = $repopan->findOneByUser($user); //ceci est dans le panierrepository

        return $this->render('bijouterie/panier.html.twig', [
            'panier' => $panier,
        ]);
    }

    /**
     * @Route("/panier/validpanier", name="validpanier")
     * @Security("is_granted('ROLE_USER')")
     */
    public function validPanier(PanierRepository $repopan, ObjectManager $manager) {

        $user = $this->getUser();
        $panier = $repopan->findOneByUser($user); //ceci est dans le panierrepository
        
        if($panier != null){
            $panier->setDatePanier(new \DateTime('now', new \DateTimeZone("Europe/Paris")));

            $manager->persist($panier);
            $manager->flush();

            $this->addFlash(
                'success2',
                "Votre commande de réservation a bien été envoyée !"
            );
        } 
        
        return $this->redirectToRoute('panier');
    }
    
    /**
     * @Route("/panier/deletepropan/{id}", name="deletepropan")
     * @Security("is_granted('ROLE_USER')")
     */
    public function deleteProPan(Comporte $comporte, ObjectManager $manager) {

        //sécurité pour qu'un autre utilisateur ne puisse pas suppr
        if($comporte->getPanier()->getUser() != $this->getUser()) { 
            throw new AccessDeniedException();
        }
        $manager->remove($comporte);
        $manager->flush();

        return $this->redirectToRoute('panier');
    }

    /**
     * @Route("/panier/{id}", name="ajouter_produit")
     * @Security("is_granted('ROLE_USER')")
     */
    public function ajouterProduit(Produit $produit, ComporteRepository $repocomp, PanierRepository $repopan, ObjectManager $manager) {
        
        // on va chercher le user
        $user = $this->getUser();

        // Tester si le panier existe
        // -> Chercher dans la bdd (Repository) le panier s'il y en a un ou non
        // -> Ta valeur $panier = Sur ton repo -> findOneByUser($user) 
        // ton panier vaudra null ou un panier
        // Si le panier est null, il faut en créer un
        // Sinon tu affiches false

        $panier = $repopan->findOneByUser($user); //ceci est dans le panierrepository
        
        if($panier == null){
            $panier = new Panier();
            $panier->setUser($user);
        } 

        $manager->persist($panier);
        $manager->flush();

        $compo = $repocomp->findOneBy(['panier' => $panier, 'produit' => $produit]);

        // if(ce que tu veux){
        //     $test = true;
        //     die(var_dump($test));
        // } else {
        //     $test = false;
        //     die(var_dump($test));
        // }

        // --> set c'est modifier et get récupérer
        
        // Si ta "compo" n'a rien trouvé (donc null) c'est qu'il n'y est pas, donc tu peux créer ton "comporte"
        // Sinon il existe donc tu ne le crées pas, pour eviter les doublons

        if($produit != null && $compo == null){
            $comporte = new Comporte();
            $comporte->setPanier($panier)
                     ->setProduit($produit)
                     ->setQuantite(1);

            $manager->persist($comporte);
            $manager->flush();    
        }
        
        return new JsonResponse();
    }

    /**
     * @Route("/panier/quantite/{id}", name="quantite")
     * @Security("is_granted('ROLE_USER')")
     */
    public function quantite(ObjectManager $manager, ComporteRepository $repocomp, PanierRepository $repopan) {

        $user = $this->getUser();

        $panier = $repopan->findOneByUser($user); //ceci est dans le panierrepository

        $quant = $repocomp->findOneBy(['quantite' => $quantite]);

        if($quant == 1){
            $comporte->setQuantite($quantite);

            $manager->persist($comporte);
            $manager->flush(); 
        }

        return $this->redirectToRoute('panier');
    }

    /**
     * @Route("/panier/deletpropan/{id}", name="deletpropan")
     * @Security("is_granted('ROLE_USER')")
     */
    public function deletpropan(Produit $produit, ObjectManager $manager, ComporteRepository $repocomp, PanierRepository $repopan) {

        $user = $this->getUser();

        $panier = $repopan->findOneByUser($user); //ceci est dans le panierrepository

        $compo = $repocomp->findOneBy(['panier' => $panier, 'produit' => $produit]);
 
        if($compo != null){
            $manager->remove($compo);
            $manager->flush();
        }

        return new JsonResponse();
    }

    /**
     * @Route("/profil", name="profil")
     * @Security("is_granted('ROLE_USER')")
     */
    public function profil(PanierRepository $repopan) {

        $user = $this->getUser();
        $paniers = $repopan->findValidByUser($user); //ceci est dans le panierrepository

        return $this->render('bijouterie/profil.html.twig', [
            'paniers' => $paniers
        ]);
    }

    /**
     * @Route("/modifprofil", name="modifprofil")
     * @Security("is_granted('ROLE_USER')")
     */
    public function modifprofil(Request $request, ObjectManager $manager) {

        $user = $this->getUser();
        $formprofil = $this->createForm(ProfilType::class, $user);
        $formprofil->handleRequest($request);

        if($formprofil->isSubmitted() && $formprofil->isValid()) {
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre profil a bien été modifié"
            );
        }

        return $this->render('bijouterie/modifprofil.html.twig', [
            'formprofil' => $formprofil->createView()
        ]);
    }
}
